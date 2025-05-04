export class Http {
	static async get(uri) {
		const request = await fetch(uri, { // await, т.к fetch всегда возвращает promise;
			method: 'GET',
			headers: {
				'Content-Type': 'application/json'
			}
		});
		const answer = await request.json();
		return answer;
	}

	static async post(uri, jsonData) {
		const request = await fetch(uri, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: jsonData
		});
		const answer = await request.json();
		return answer;
	}

	static async delete(uri) {
		const request = await fetch(uri, {
			method: 'DELETE',
			headers: {
				'Content-Type': 'application/json'
			}
		});
		const answer = await request.json();
		return answer;
	}
}