
var videoconfId = $("#videoconf-id").text();
var meetingEndUrl = $("#meeting-end").attr("href");

$.ajax({
    type: "get",
    url: "/videoconference/videoconf/json-videoconf-data",
    data: "id=" + videoconfId,
    dataType: "json",
    success: function (response, status) {
        showVideoconference(response);
    },

});

function showVideoconference(data) {
    var domain = data.domain+"?lang=it";
    var options = {
        roomName: data.roomName,
        width: '100%',
        height: '100%',
        parentNode: document.querySelector('#meet'),
        configOverwrite: {},
        interfaceConfigOverwrite: {
            filmStripOnly: false,
            VERTICAL_FILMSTRIP: false,
            FILM_STRIP_MAX_HEIGHT: 200,
            MOBILE_APP_PROMO: false,
            TOOLBAR_BUTTONS: [
                // main toolbar
                'microphone', 'camera', 'desktop', 'invite', 'fullscreen', 'fodeviceselection', 'hangup',
                // extended toolbar
                'contacts', 'chat', 'recording', 'etherpad', 'sharedvideo', 'settings', 'raisehand', 'videoquality', 'filmstrip'],
            /**
             * Main Toolbar Buttons
             * All of them should be in TOOLBAR_BUTTONS
             */
            MAIN_TOOLBAR_BUTTONS: ['microphone', 'camera', 'desktop', 'fullscreen', 'fodeviceselection', 'hangup']
        }
    }
    var api = new JitsiMeetExternalAPI(domain, options);
    api.executeCommand('avatarUrl', data.avatar);
    api.executeCommand('displayName', data.displayName);
    api.addEventListener('readyToClose', function () {
        api.dispose();
        document.location = meetingEndUrl;
    });
}

