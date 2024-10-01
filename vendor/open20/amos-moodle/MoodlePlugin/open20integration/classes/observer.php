<?php

defined('MOODLE_INTERNAL') || die();

class local_open20integration_observer {

    public static function observe_course($eventdata) {
        $config = get_config('local_open20integration');
        if (isset($config->callbacks_url) && $config->callbacks_url != '') {
            $ch = curl_init();
            
            $authtokenData = self::generateAuthToken();

            curl_setopt($ch, CURLOPT_URL, $config->callbacks_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                'objectid' => $eventdata->objectid,
                'courseid' => $eventdata->courseid,
                'action' => $eventdata->action,
                'target' => $eventdata->target,
                'authtoken' => $authtokenData->token,
                'timestamp' => $authtokenData->timestamp,
            ));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $curlResult = curl_exec($ch);
            if($curlResult === false){
                error_log("Callback verso ".$config->callbacks_url." fallita: ".curl_error($ch));
            }
            curl_close($ch);
        }
    }

    public static function observe_user_enrolment($eventdata) {
        $config = get_config('local_open20integration');
        if (isset($config->callbacks_url) && $config->callbacks_url != '') {
            $ch = curl_init();
            
            $authtokenData = self::generateAuthToken();

            curl_setopt($ch, CURLOPT_URL, $config->callbacks_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                'objectid' => $eventdata->objectid,
                'action' => $eventdata->action,
                'target' => $eventdata->target,
                'relateduserid' => $eventdata->relateduserid,
                'courseid' => $eventdata->courseid,
                'authtoken' => $authtokenData->token,
                'timestamp' => $authtokenData->timestamp,
            ));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $curlResult = curl_exec($ch);
            if($curlResult === false){
                error_log("Callback verso ".$config->callbacks_url." fallita: ".curl_error($ch));
            }
            curl_close($ch);
        }
    }
    
    public static function observe_course_category($eventdata) {
        $config = get_config('local_open20integration');
        if (isset($config->callbacks_url) && $config->callbacks_url != '') {
            $ch = curl_init();
            
            $authtokenData = self::generateAuthToken();

            curl_setopt($ch, CURLOPT_URL, $config->callbacks_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                'objectid' => $eventdata->objectid,
                'action' => $eventdata->action,
                'target' => $eventdata->target,
                'authtoken' => $authtokenData->token,
                'timestamp' => $authtokenData->timestamp,
            ));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $curlResult = curl_exec($ch);
            if($curlResult === false){
                error_log("Callback verso ".$config->callbacks_url." fallita: ".curl_error($ch));
            }
            curl_close($ch);
        }
    }
    
    private static function generateAuthToken(){
        $authtokenData = new stdClass();
        $authtokenData->timestamp = time();
        $authtokenData->token = hash('sha256',get_config('local_open20integration')->secretkey.$authtokenData->timestamp);
        return $authtokenData;
    }

}
