<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->libdir . '/badgeslib.php');

class local_open20integration_external extends external_api {


    
    /**
     * Describes the parameters for get_course_badges.
     *
     * @return external_function_parameters
     */
    public static function get_course_badges_parameters() {
        return new external_function_parameters (
            array(
                'courseid' => new external_value(PARAM_INT, 'Filter badges by course id, empty all the courses'),
            )
        );
    }
    /**
     * Returns the list of badges awarded to a user.
     *
     * @param int $courseid     course id
     * @return array array containing warnings and the awarded badges
     * @throws moodle_exception
     */
    public static function get_course_badges($courseid) {
        global $CFG;
        $warnings = array();
        $params = array(
            'courseid' => $courseid,
        );
        $params = self::validate_parameters(self::get_course_badges_parameters(), $params);
        
        if (empty($CFG->enablebadges)) {
            throw new moodle_exception('badgesdisabled', 'badges');
        }
        if (empty($CFG->badges_allowcoursebadges)) {
            throw new moodle_exception('coursebadgesdisabled', 'badges');
        }

        $coursebadges = badges_get_badges(BADGE_TYPE_COURSE, $params['courseid']);


        $result = array();
        $result['badges'] = array();
        $result['warnings'] = $warnings;
        foreach ($coursebadges as $badge) {
            $context = ($badge->type == BADGE_TYPE_SITE) ? context_system::instance() : context_course::instance($badge->courseid);
            $badge->badgeurl = moodle_url::make_webservice_pluginfile_url($context->id, 'badges', 'badgeimage', $badge->id, '/','f1')->out(false);
			$result['badges'][] = array(
				'id' => $badge->id,
				'name' => $badge->name,
				'description' => $badge->description,
				'badgeurl' => $badge->badgeurl,
				'issuername' => $badge->issuername,
				'issuerurl' => $badge->issuerurl,
				'issuercontact' => $badge->issuercontact,
				'expiredate' => $badge->expiredate,
				'courseid' => $badge->courseid,
			);
        }
        return $result;
    }
    
    
    /**
     * Describes the get_course_badges return value.
     *
     * @return external_single_structure
     */
    public static function get_course_badges_returns() {
        return new external_single_structure(
            array(
                'badges' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'Badge id.', VALUE_OPTIONAL),
                            'name' => new external_value(PARAM_TEXT, 'Badge name.'),
                            'description' => new external_value(PARAM_NOTAGS, 'Badge description.'),
                            'badgeurl' => new external_value(PARAM_URL, 'Badge URL.'),
                            'issuername' => new external_value(PARAM_NOTAGS, 'Issuer name.'),
                            'issuerurl' => new external_value(PARAM_URL, 'Issuer URL.'),
                            'issuercontact' => new external_value(PARAM_RAW, 'Issuer contact.'),
                            'expiredate' => new external_value(PARAM_INT, 'Expire date.', VALUE_OPTIONAL),
							'courseid' =>new external_value(PARAM_INT, 'Course id.')
                        )
                    )
                ),
                'warnings' => new external_warnings(),
            )
        );
    }      
      
    public static function get_course_img_parameters() {
        return new external_function_parameters(array(
            'courseid' => new external_value(PARAM_INT, 'id of course'),
                )
        );
    }

    public static function get_course_img_returns() {
        return new external_single_structure(
                array(
            'courseid' => new external_value(PARAM_INT, 'id of course'),
            'imageurl' => new external_value(PARAM_URL, 'img url of course'),
                )
        );
    }

    public static function get_course_img($courseid) {
        global $CFG;
        require_once($CFG->libdir . '/coursecatlib.php');

        $_courseid = new stdClass;
        $_courseid->id = $courseid;
        $course = new course_in_list($_courseid);
        $imageurl = null;
        foreach ($course->get_course_overviewfiles() as $file) {
            if ($file->is_valid_image()) {
                $imagepath = '/' . $file->get_contextid() .
                        '/' . $file->get_component() .
                        '/' . $file->get_filearea() .
                        $file->get_filepath() .
                        $file->get_filename();
                $imageurl = file_encode_url($CFG->wwwroot . '/pluginfile.php', $imagepath, false);
                // Use the first image found.
                break;
            }
        }
        return array('courseid' => $courseid, 'imageurl' => $imageurl);
    }

    public static function get_courses_imgs_parameters() {
        return new external_function_parameters(array());
    }

    public static function get_courses_imgs_returns() {
        return new external_multiple_structure(new external_single_structure(
                array(
            'courseid' => new external_value(PARAM_INT, 'id of course'),
            'imageurl' => new external_value(PARAM_URL, 'img url of course'),
                )
                )
        );
    }

    public static function get_courses_imgs() {
        global $CFG;
        require_once($CFG->libdir . '/coursecatlib.php');

        $courses_imgs = array();

        $courses = get_courses();
        foreach ($courses as $_course) {
            $course = new course_in_list($_course);
            $imageurl = null;
            foreach ($course->get_course_overviewfiles() as $file) {
                if ($file->is_valid_image()) {
                    $imagepath = '/' . $file->get_contextid() .
                            '/' . $file->get_component() .
                            '/' . $file->get_filearea() .
                            $file->get_filepath() .
                            $file->get_filename();
                    $imageurl = file_encode_url($CFG->wwwroot . '/pluginfile.php', $imagepath, false);
                    // Use the first image found.
                    break;
                }
            }
            array_push($courses_imgs, array('courseid' => $course->id, 'imageurl' => $imageurl));
        }
        return $courses_imgs;
    }

    public static function get_scorm_data_by_cm_parameters() {
        return new external_function_parameters(array(
            'cmid' => new external_value(PARAM_INT, 'id of course module'),
        ));
    }

    public static function get_scorm_data_by_cm_returns() {
        return new external_single_structure(
                array(
            'scormstatus' => new external_value(PARAM_CLEANHTML, 'scorm attempt status'),
                    'scormname' => new external_value(PARAM_TEXT, 'scorm name'),
            'playerurl' => new external_value(PARAM_URL, 'player url'),
                )
        );
    }

    public static function get_scorm_data_by_cm($cmid) {
        global $CFG, $USER, $DB;
        require_once($CFG->dirroot . '/mod/scorm/locallib.php');

        // Recupero l'id dell'utente loggato
        $user = new stdClass();
        $user->id = $USER->id;


        if (!empty($cmid)) {
            if (!$cm = get_coursemodule_from_id('scorm', $cmid, 0, true)) {
                print_error('invalidcoursemodule');
            }
//            if (!$course = $DB->get_record("course", array("id" => $cm->course))) {
//                print_error('coursemisconf');
//            }
            if (!$scorm = $DB->get_record("scorm", array("id" => $cm->instance))) {
                print_error('invalidcoursemodule');
            }
        } else {
            print_error('missingparameter');
        }

        $result = array();
        $result['scormstatus'] = scorm_get_attempt_status($user, $scorm);
        $result['scormname'] = $scorm->name;
        if($scorm->launch){
            $playerurl = $CFG->wwwroot . '/mod/scorm/player.php?scoid=' . $scorm->launch . '&cm=' . $cm->id . '&display=popup&mode=normal';
        } else {
            $playerurl = '';
        }
        $result['playerurl'] = $playerurl;

        return $result;
    }
    
    public static function add_siteadmin_parameters() {
        return new external_function_parameters(array(
            'userid' => new external_value(PARAM_INT, 'id of the user to promote'),
        ));
    }

    public static function add_siteadmin_returns() {
        return new external_single_structure(
                array(
            'currentsiteadmins' => new external_value(PARAM_TEXT, 'list of current siteadmins'),
                )
        );
    }

    public static function add_siteadmin($userid) {
        global $CFG, $USER;
        //require_once($CFG->dirroot . '/mod/scorm/locallib.php');

        // Recupero l'id dell'utente loggato
        if(!is_siteadmin($USER->id)){
            print_error('notauthorized');
        } else{
            $admins = [];
            foreach(explode(',',$CFG->siteadmins) as $admin){
                $admins[]=$admin;
            }
            if(!in_array($userid,$admins)){
                //inserisco il nuovo utente se non presente
                $admins[]=$userid;
                set_config('siteadmins', implode(',', $admins));
            }
            
        }
        $result['currentsiteadmins'] = $CFG->siteadmins;
        return $result;
    }
	
	public static function generate_user_token_parameters() {
		return new external_function_parameters(array(
			'userid' => new external_value(PARAM_INT, 'id of the user to generate the token for'),
			'serviceshortname' => new external_value(PARAM_TEXT, 'the name of the service to generate the token for'),
		));
    }

    public static function generate_user_token_returns() {
        return new external_single_structure(
                array(
            'userid' => new external_value(PARAM_INT, 'id of the user owner of the token'),
			'token' => new external_value(PARAM_TEXT, 'The Token'),
                )
        );
    }

    public static function generate_user_token($userid, $serviceshortname) {
        global $DB, $USER;

        // Verifico che l'utente richiedente sia admin
        if(!is_siteadmin($USER->id)){
            throw new moodle_exception('notauthorized');
        }
		
		$user = $DB->get_record('user',array('id' => $userid));
		if($user){
			core_user::require_active_user($USER, true, true);
		} else {
			throw new moodle_exception('usernotfound');
		}
		
		// Verifico che esista il service
		$service = $DB->get_record('external_services', array('shortname' => $serviceshortname, 'enabled' => 1));
		if (empty($service)) {
			throw new moodle_exception('servicenotavailable', 'webservice');
		}
		
		// Verifico che non esista già un token valido
		$conditions = array(
			'userid' => $userid,
			'externalserviceid' => $service->id,
			'tokentype' => EXTERNAL_TOKEN_PERMANENT
		);
		$tokens = $DB->get_records('external_tokens', $conditions, 'timecreated ASC');

		// A bit of sanity checks.
		foreach ($tokens as $key => $token) {
			// Checks related to a specific token. (script execution continue).
			$unsettoken = false;
			// If sid is set then there must be a valid associated session no matter the token type.
			if (!empty($token->sid)) {
				if (!\core\session\manager::session_exists($token->sid)) {
					// This token will never be valid anymore, delete it.
					$DB->delete_records('external_tokens', array('sid' => $token->sid));
					$unsettoken = true;
				}
			}

			// Remove token is not valid anymore.
			if (!empty($token->validuntil) and $token->validuntil < time()) {
				$DB->delete_records('external_tokens', array('token' => $token->token, 'tokentype' => EXTERNAL_TOKEN_PERMANENT));
				$unsettoken = true;
			}

			// Remove token if its ip not in whitelist.
			if (isset($token->iprestriction) and !address_in_subnet(getremoteaddr(), $token->iprestriction)) {
				$unsettoken = true;
			}

			if ($unsettoken) {
				unset($tokens[$key]);
			}
		}

		// If some valid tokens exist then use the most recent.
		if (count($tokens) > 0) {
			$token = array_pop($tokens)->token;
		} else {
			//Genero il token
			$tokentype = EXTERNAL_TOKEN_PERMANENT;
			$contextorid = 1;
			$validuntil=0;
			$iprestriction='';
			$token = external_generate_token($tokentype, $service, $userid, $contextorid, $validuntil, $iprestriction);
			external_log_token_request($token);
		}
        return array(
			'userid' => $userid,
			'token' => $token,
		);
    }


    /**
     * Describes the parameters for get_enrol_info.
     *
     * @return external_function_parameters
     */
    public static function get_enrol_info_parameters() {
        return new external_function_parameters(array(
            'courseid' => new external_value(PARAM_INT, 'id of course'),
                )
        );
    }

    /**
     * 
     * @return \external_multiple_structure
     */
    public static function get_enrol_info_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'id', VALUE_OPTIONAL),
                    'enrol' => new external_value(PARAM_TEXT, 'enrol'),
                    'status' => new external_value(PARAM_INT, 'status'),
                    'courseid' =>new external_value(PARAM_INT, 'Course id.'),
                    'cost' => new external_value(PARAM_TEXT, 'cost'),
                    'currency' => new external_value(PARAM_TEXT, 'currency'),
                )
            )
        );
    }

    /**
     * Returns the course enrol info, for paypal enrollment method cost, currency too
     *
     * @param int $courseid     course id
     * @return array array containing enrol info
     * @throws moodle_exception
     */
    public static function get_enrol_info($courseid) {
        global $DB;

        $enrol = $DB->get_records(
            'enrol',
            array('courseid'=>$courseid, 'status'=>ENROL_INSTANCE_ENABLED),
            'sortorder,id'
        );

        return $enrol;
    }

    /**
     * 
     * @return \external_function_parameters
     */
    public static function enrol_user_via_paypal_parameters() {
        return new external_function_parameters(
            array(
                'enrolid' => new external_value(PARAM_INT, 'id of enrol method'),
                'userid' => new external_value(PARAM_INT, 'id the user to enroll'),
            )
        );
    }

    /**
     * 
     * @return \external_single_structure
     */
    public static function enrol_user_via_paypal_returns() {
        return new external_single_structure(
            array(
                'enrolid' => new external_value(PARAM_TEXT, 'id of the enrol record created'),
            )
        );
    }

    /**
     * 
     * @global type $DB
     * @global type $USER
     * @param type $enrolid
     * @param type $userid
     * @return type
     * @throws moodle_exception
     */
    public static function enrol_user_via_paypal($enrolid = null, $userid = null) {
        global $DB, $USER;

        $user = $DB->get_record('user',array('id' => $userid));
        if ($user) {
            core_user::require_active_user($USER, true, true);
        } else {
            throw new moodle_exception('usernotfound');
        }
		
        // Verifico che non esista già un token valido
        $conditions = array(
            'userid' => $userid,
            'enrolid' => $enrolid,
        );
        
        $enrolled = $DB->get_record('user_enrolments', $conditions);
        // Not present? Add it!
        if (empty($enrolled)) {
            $enrollment = new stdClass();
            $enrollment->userid = $userid;
            $enrollment->enrolid = $enrolid;
            $enrollment->modifierid = $userid;
            $enrollment->timestart = time();
            $enrollment->timecreated = time();
            $enrollment->timemodified = time();
            
            $enrolid = $DB->insert_record('user_enrolments', $enrollment);
        }
        
        return array(
            'enrolid' => $enrolid,
        );
    }

}
