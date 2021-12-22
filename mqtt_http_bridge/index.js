const mqtt = require('mqtt');
const {post_detections} = require('./handler/HttpHandler');

const client = mqtt.connect("mqtt://mosquitto");
(async () => {
    client.subscribe('vtms/detections');
    client.on('message', (topic, payload, packet) => {
        try {
            setTimeout(post_detections(payload), 0);
        } catch(e) {
            console.log("Error");
        }
    });
})();
