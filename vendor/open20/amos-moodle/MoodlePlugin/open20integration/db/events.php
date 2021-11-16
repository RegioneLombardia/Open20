<?php

defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname' => '\core\event\course_created',
        'callback' => 'local_open20integration_observer::observe_course',
    ),
    array(
        'eventname' => '\core\event\course_deleted',
        'callback' => 'local_open20integration_observer::observe_course',
    ),
    array(
        'eventname' => '\core\event\course_updated',
        'callback' => 'local_open20integration_observer::observe_course',
    ),
    array(
        'eventname' => '\core\event\user_enrolment_created',
        'callback' => 'local_open20integration_observer::observe_user_enrolment',
    ),
    array(
        'eventname' => '\core\event\user_enrolment_deleted',
        'callback' => 'local_open20integration_observer::observe_user_enrolment',
    ),
    array(
        'eventname' => '\core\event\user_enrolment_updated',
        'callback' => 'local_open20integration_observer::observe_user_enrolment',
    ),
    array(
        'eventname' => '\core\event\course_category_created',
        'callback' => 'local_open20integration_observer::observe_course_category',
    ),
    array(
        'eventname' => '\core\event\course_category_deleted',
        'callback' => 'local_open20integration_observer::observe_course_category',
    ),
    array(
        'eventname' => '\core\event\course_category_updated',
        'callback' => 'local_open20integration_observer::observe_course_category',
    ),
);
