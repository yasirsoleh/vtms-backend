const http = require('http');

const options = {
    host: 'vtms_backend.test',
    path: '/api/detections',
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json; charset=UTF-8',
      'Authorization': ''
    }
}

function post_detections(payload) {
    const {token, plate_number} = JSON.parse(payload.toString());
    options.headers.Authorization = 'Bearer '+ token;

    const data = JSON.stringify({
        'plate_number': plate_number
    });

    const req = http.request(options, res => {
        console.log(`statusCode: ${res.statusCode}`);
        res.on('data', d => {
            process.stdout.write(d);
        });
    });

    req.on('error', error => {
        console.error(error);
    });

    req.write(data);
    req.end();
}

module.exports = {post_detections}
