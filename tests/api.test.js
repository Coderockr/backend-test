const { expect } = require("@jest/globals");
const request = require("supertest");

const base_url = "http://172.30.64.1"; //172.30.64.1 == Default WSL IP
var admin_jwt = "";
var investment_user_id = "";
var investment_id = "";

describe('Admin user endpoint', () => {
	test('should be a unsuccessful login', async () => {

		const response = await request(base_url)
			.post("/api/v1/admin/login")
			.send({user: 'unknown user', password: 'no pass'})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(401);
		expect(response.body.status).toBe(3); // Code for wrong user or password
	});

	test('should be a successful login', async () => {
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



describe('Investor user endpoint', () => {
	test('should create a user', async () => {
		const response = await request(base_url)
			.put("/api/v1/investments/user/create")
			.send({_credentials: {token: admin_jwt}, name: "kyle"})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(200);
		expect(response.body.status).toBe(0);
		investment_user_id = response.body.owner_id;
	});

	test('should return a empty investments list', async () => {
		const response = await request(base_url)
			.post("/api/v1/investments/user/investments/list")
			.send({_credentials: {token: admin_jwt}, user_id: investment_user_id, format: "TIMESTAMP", decimals: 2})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(200);
		expect(response.body.status).toBe(0);
	});
});


describe('Investments endpoint', () => {
	test('should create a investment', async () => {
		const response = await request(base_url)
			.put("/api/v1/investments/create")
			.send({
				_credentials: {
					token: admin_jwt
				},
				owner_id: investment_user_id,
				creation_date: {
					format: "TIMESTAMP",
					value: 19595
				},
				investment_value: {
					decimals: 2,
					value: 1000
				}
			})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(200);
		expect(response.body.status).toBe(0);
		investment_id = response.body.investment_id;
	});

	test('should return a investment', async () => {
		const response = await request(base_url)
			.post("/api/v1/investments/view")
			.send({_credentials: {token: admin_jwt}, investment_id: investment_id, format: "TIMESTAMP", decimals: 2})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(200);
		expect(response.body.status).toBe(0);
	});

	
	test('check created investment initial data', async () => {
		const response = await request(base_url)
			.post("/api/v1/investments/view")
			.send({_credentials: {token: admin_jwt}, investment_id: investment_id, format: "TIMESTAMP", decimals: 2})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(200);
		expect(response.body.status).toBe(0);
		expect(response.body.already_withdrawed).toBe(false);
		expect(response.body.creation_date.format).toBe("TIMESTAMP");
		expect(response.body.creation_date.value).toBe(19595);
		expect(response.body.initial_amount.decimals).toBe(2);
		expect(response.body.initial_amount.value).toBe(1000);
	});


	test('should withdrawal a investment and return the values', async () => {
		const response = await request(base_url)
			.post("/api/v1/investments/withdrawal")
			.send({_credentials: {token: admin_jwt}, investment_id: investment_id, format: "TIMESTAMP", decimals: 2})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(200);
		expect(response.body.status).toBe(0);
	});

	
	test('check withdrawed investment data', async () => {
		const response = await request(base_url)
			.post("/api/v1/investments/view")
			.send({_credentials: {token: admin_jwt}, investment_id: investment_id, format: "TIMESTAMP", decimals: 2})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(200);
		expect(response.body.status).toBe(0);
		expect(response.body.already_withdrawed).toBe(true);
	});
});


describe('Testing results', () => {
	test('should have one investment', async () => {
		const response = await request(base_url)
			.post("/api/v1/investments/user/investments/list")
			.send({_credentials: {token: admin_jwt}, user_id: investment_user_id, format: "TIMESTAMP", decimals: 2})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(200);
		expect(response.body.status).toBe(0);
		expect(response.body.investments.length).toBe(1);
	});

	
	test('investment needs to be withdrawed status', async () => {
		const response = await request(base_url)
			.post("/api/v1/investments/user/investments/list")
			.send({_credentials: {token: admin_jwt}, user_id: investment_user_id, format: "TIMESTAMP", decimals: 2})
			.set('Accept', 'application/json')
			.expect('Content-Type', /json/)
			.expect(200);
		expect(response.body.status).toBe(0);
		expect(response.body.investments[0].already_withdrawed).toBe(true);
	});
});
