function supportsTemplate() {
    return 'content' in document.createElement('template');
}

function getTime() {
    const minutes = 1000 * 60;
    const hours = minutes * 60;
    const days = hours * 24;
    const years = days * 365;
    let d = new Date();
    let t = d.getTime();

    return Math.round(t / years);
}

if (supportsTemplate()) {
    // Good to go!

    let socket = new WebSocket("wss://" + wsLink);

    socket.onopen = function (e) {
        socket.send(JSON.stringify({
            "action": "setName",
            "name": currentUser,
            "fullName": fullName,
            "avatarImg": avatarImg
        }))
        socket.send(JSON.stringify({"action": "contactList"}))
    }
    // send message from the form

    document.forms.sendChat.onsubmit = function (e) {
        let outgoingMessage = JSON.stringify({"action": "chat", "message": this.message.value});

        socket.send(outgoingMessage);
        return false;
    };


// message received - show the message in div#messages
    socket.onmessage = function (event) {
        let message = JSON.parse(event.data);
        if (message.type && message.type === "chat" && message.from !== currentUser) {
            let t = document.querySelector("#chat-from");

            t.content.querySelector('img').src = message.avatarImg;
            t.content.querySelector('.direct-chat-name').textContent = message.fullName.length === 0 ? message.from : message.fullName;
            t.content.querySelector('.direct-chat-timestamp').innerHTML = moment().format('MMMM Do YYYY, h:mm:ss a');
            t.content.querySelector('.direct-chat-text').textContent = message.message;

            let clone = document.importNode(t.content, true);
            document.querySelector(".direct-chat-messages").append(clone);
        } else if (message.type && message.type === "chat" && message.from === currentUser) {
            let t = document.querySelector("#chat-to");

            t.content.querySelector('.direct-chat-timestamp').innerHTML = moment().format('MMMM Do YYYY, h:mm:ss a');
            t.content.querySelector('.direct-chat-text').textContent = message.message;

            let clone = document.importNode(t.content, true);
            document.querySelector(".direct-chat-messages").append(clone);
        } else if (message.type && message.type === "contactList") {

            let contactList = document.querySelector(".contacts-list");
            contactList.innerHTML = '';
            message.message.forEach(function (client, index) {
                if (currentUser !== client.name) {
                    let t = document.querySelector("#contact-list");
                    t.content.querySelector('img').src = client.avatarImg;
                    t.content.querySelector('.contacts-list-date').textContent = moment().format('MMMM Do YYYY, h:mm:ss a');
                    t.content.querySelector('.contacts-list-name').textContent = client.fullName.length === 0 ? client.name : client.fullName.length;

                    let clone = document.importNode(t.content, true);
                    contactList.append(clone);
                }
            })
        } else {
        }
        let objDiv = document.querySelector(".direct-chat-messages");
        objDiv.scrollTop = objDiv.scrollHeight;
    };
} else {
    // Use old templating techniques or libraries.
}
