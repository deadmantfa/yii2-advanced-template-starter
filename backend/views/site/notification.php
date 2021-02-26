<?php

use kartik\helpers\Html;

$this->title = 'Notification';

echo Html::button(
    'Send everyone a notification',
    [
        'class' => 'btn btn-primary',
        'onclick' => '
                $.ajax({
                    url: "/site/notification",
                    success: function(result) {
                        console.log("Completed");                    
                    }, 
                    error: function(result) {
                        console.log("server error");
                    }
                });
'
    ]
);
