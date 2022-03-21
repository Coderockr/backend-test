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
		expect(response.body.status).toBe(3); // Code for wrong user or password
	});

	test('shold be a successful login', async () => {
		//The user and password is the default for mock data, 
		//if you want to test with a real user, don't do it
		//Don't change the user and password

		const response = await request(base_url)
			.post("/api/v1/admin/login")
			.send({user: 'mocked', password: 'with mock data'})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(200);
		expect(response.body.status).toBe(0); // Code for successful login
		admin_jwt = response.body.token;
	});
});


describe('/api/v1/admin', () => {
	test('shold create a user', async () => {
		const response = await request(base_url)
			.post("/api/v1/admin/login")
			.send({user: 'unknown user', password: 'no pass'})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(401);
		expect(response.body.status).toBe(3); // Code for wrong user or password
	});

	test('shold be a successful login', async () => {
		//The user and password is the default for mock data, 
		//if you want to test with a real user, don't do it
		//Don't change the user and password

		const response = await request(base_url)
			.post("/api/v1/admin/login")
			.send({user: 'mocked', password: 'with mock data'})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(200);
		expect(response.body.status).toBe(0); // Code for successful login
		admin_jwt = response.body.token;
	});
});