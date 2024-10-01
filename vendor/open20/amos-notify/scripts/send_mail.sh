#!/bin/bash
#First Mail Batch
php yii notify/notifier/mail-channel --dayMails

while [ $? -eq 0 ]; do
    echo "WAIT 10 SECS"
    sleep 10

    # Limit while cycle
    ((c++)) && ((c==100)) && break

    # Send Mails In Loop
    php yii notify/notifier/mail-channel --dayMails
done