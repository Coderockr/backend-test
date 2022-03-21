const { expect } = require("@jest/globals");
const request = require("supertest");

const base_url = "http://localhost";
var admin_jwt = "";

describe('/api/v1/admin', () => {

	test('shold be a unsuccessful login', async () => {
		const response = await request(base_url)
			.post("/api/v1/admin/login")
			.send({user: 'unknown user', password: 'no pass'})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(401);
	});

	test('shold be a successful mock data login', async () => {
		const response = await request(base_url)
			.post("/api/v1/admin/login")
			.send({user: 'mocked', password: 'with mock data'})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(200);
		admin_jwt = response.body.token;
	});
	
});