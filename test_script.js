import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
    vus: 50,             // virtual users
    duration: '1m',      // test execution duration
    thresholds: {
        http_req_duration: ['p(95)<500'], // 95% of requests must be faster than 500ms
    },
};

export default function () {
    const url = 'http://host.docker.internal:8088/api/leads';
    const payload = JSON.stringify({
        firstName: "Test",
        lastName: "User",
        email: "check@email.com",
        phone: "9295032303",
        dateOfBirth: "2002-01-02",
        dynamicData: {
            middleName: 1,
            hobbies: ["tennis", "video games"]
        }
    });

    const params = {
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer testtoken123',
        },
    };

    let res = http.post(url, payload, params);

    check(res, {
        'status is 201': (r) => r.status === 201,
    });

    sleep(0.1);
}