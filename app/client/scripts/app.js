import { Html } from "./html.js";
import { Http } from "./http.js";
import { Utils } from "./utils.js";

class App {
	static root = document.getElementById('root');

	static renderLogin() {
		App.root.innerHTML = Html.basicHeader() + Html.logForm() + Html.footer();
		document.querySelector('form').addEventListener('submit', App.login);

		document.querySelector('#logBtn').addEventListener('click', App.renderLogin);
		document.querySelector('#regBtn').addEventListener('click', App.renderRegister);
	}

	static renderRegister() {
		App.root.innerHTML = Html.basicHeader() + Html.regForm() + Html.footer();
		document.querySelector('form').addEventListener('submit', App.registration);

		document.querySelector('#logBtn').addEventListener('click', App.renderLogin);
		document.querySelector('#regBtn').addEventListener('click', App.renderRegister);
	}

	static renderTaskArea() {
		App.root.innerHTML = Html.userHeader() + Html.createArea() + Html.footer();

		//document.querySelector('#outBtn').addEventListener('click', );
		//document.querySelector('#delBtn').addEventListener('click', );
	}
	
	static async registration(event) {
		event.preventDefault();

		const user_name = document.querySelector('input[name="user_name"]').value;
		const user_password = document.querySelector('input[name="user_password"]').value;

		const data = {
			user_name: user_name,
			user_password: user_password
		}

		const json = JSON.stringify(data);
		const answer = await Http.post('/api/user', json);

		console.log(answer.error);

		if (answer.status !== undefined) Utils.renderMessage(answer.status);
		else if (answer.error !== undefined) Utils.renderError(answer.error);
	}

	static async login(event) {
		event.preventDefault();

		const user_name = document.querySelector('input[name="user_name"]').value;
		const user_password = document.querySelector('input[name="user_password"]').value;

		const data = {
			user_name: user_name,
			user_password: user_password
		}

		const json = JSON.stringify(data);
		const answer = await Http.post('/api/auth', json);

		if (answer.error !== undefined) Utils.renderError(answer.error);
		else if (answer.reload === true) App.run();
	}

	static run() {
		const user_id = Utils.getCookieValue('user_id');
		const user_name = Utils.getCookieValue('user_name');

		if (!user_id || !user_name) {
			this.renderLogin();
		} else {
			this.renderTaskArea();
		}
	}
}

App.run();