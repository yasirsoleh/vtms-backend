const mqtt = require('mqtt');
const client = mqtt.connect("mqtt://localhost");
const http = require('http');

const options = {
    host: 'localhost',
    path: '/api/detections',
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json; charset=UTF-8',
      'Authorization': ''
    }
};

(async () => {
    client.subscribe('vtms/detections');
    client.on('message', (topic, payload, packet) => {
        try {
            const {token, plate_number} = JSON.parse(payload.toString());
            //console.log(token);

            options.headers.Authorization = 'Bearer '+ token;

            const data = JSON.stringify({
                'plate_number': plate_number
            });

            const req = http.request(options, res => {
                console.log(`statusCode: ${res.statusCode}`)
                res.on('data', d => {
                    process.stdout.write(d)
                })
            });

            req.on('error', error => {
                console.error(error)
            });

            req.write(data);
            req.end();
        } catch(e) {
            console.log("Error");
        }
    });
})();
