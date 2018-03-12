<?php

return [

    'token' => env('SLACK_EVENTS_TOKEN'),

    /*
     * Here you define the jobs that should be run when events are received by
     * the application.
     *
     * You can find a list of Slack event types at
     * https://api.slack.com/events/api.
     */
    'jobs' => [
        // 'member_joined_channel' => \App\Jobs\SlackEvents\HandleMemberJoinedChannel::class,
        // 'reaction_added' => \App\Jobs\SlackEvents\HandleReactionAdded::class,
    ],

];
