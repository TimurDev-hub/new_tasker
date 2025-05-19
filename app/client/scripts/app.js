import { Html } from "./html.js";
import { Http } from "./http.js";
import { Utils } from "./utils.js";

class App {
	static root = document.getElementById('root');

	static renderLogin() {
		App.root.innerHTML = Html.basicHeader() + Html.logForm() + Html.footer();
		document.querySelector('#logForm').addEventListener('submit', App.login);
		document.querySelector('#logBtn').addEventListener('click', App.renderLogin);
		document.querySelector('#regBtn').addEventListener('click', App.renderRegister);
	}

	static renderRegister() {
		App.root.innerHTML = Html.basicHeader() + Html.regForm() + Html.footer();
		document.querySelector('#regForm').addEventListener('submit', App.registration);
		document.querySelector('#logBtn').addEventListener('click', App.renderLogin);
		document.querySelector('#regBtn').addEventListener('click', App.renderRegister);
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

	static async createTask(event) {
		event.preventDefault();
		const task_title = document.querySelector('input[name="task_title"]').value;
		const task_content = document.querySelector('textarea[name="task_content"]').value;
		const user_id = Utils.getCookieValue('user_id');
		const data = {
			task_title: task_title,
			task_content: task_content
		}
		const json = JSON.stringify(data);
		const answer = await Http.post('/api/task', json);
		if (answer.status !== undefined) {
			document.querySelector('input[name="task_title"]').value = '';
			document.querySelector('textarea[name="task_content"]').value = '';
			Utils.renderMessage(answer.status);
			await App.updateTaskList(user_id);
		} else if (answer.error !== undefined) Utils.renderError(answer.error);
		return false;
	}

	/*
	static async updateTaskList(user_id) {
		const request = await App.getTasks(user_id);
		if (request.reload === true) {
			const taskArea = document.querySelector('#taskArea');
			if (taskArea) taskArea.innerHTML = '';
			if (request.tasks && request.tasks.length > 0) {
				if (!document.querySelector('#taskArea')) {
					const taskWrapArea = document.querySelector('#taskWrapArea');
					if (taskWrapArea) taskWrapArea.innerHTML += Html.tasksList();
				}
				request.tasks.forEach(task => {
					document.querySelector('#taskArea').innerHTML += Html.task(task.task_title, task.task_content, task.task_id);
				});
				document.querySelectorAll('#taskArea form').forEach(form => {
					form.addEventListener('submit', App.deleteTask);
				});
			}
			const createForm = document.querySelector('#createForm');
			if (createForm) createForm.addEventListener('submit', App.createTask);
		}
	}
	*/

	static async updateTaskList(user_id) {
		const request = await App.getTasks(user_id);
		if (request.reload === true) {
			const taskArea = document.querySelector('#taskArea');
			const tasksContainer = document.querySelector('#taskArea')?.parentElement;
			if (request.tasks && request.tasks.length > 0) {
				if (!taskArea) {
					const taskWrapArea = document.querySelector('#taskWrapArea');
					if (taskWrapArea) taskWrapArea.innerHTML += Html.tasksList();
				}
				if (taskArea) taskArea.innerHTML = '';
				request.tasks.forEach(task => {
					document.querySelector('#taskArea').innerHTML += Html.task(task.task_title, task.task_content, task.task_id);
				});
				document.querySelectorAll('#taskArea form').forEach(form => {
					form.addEventListener('submit', App.deleteTask);
				});
			} else {
				if (tasksContainer) tasksContainer.remove();
			}
			const createForm = document.querySelector('#createForm');
			if (createForm) createForm.addEventListener('submit', App.createTask);
		}
	}

	static async getTasks(user_id) {
		const answer = await Http.get(`/api/task/${user_id}`);
		if (answer.error !== undefined) Utils.renderError(answer.error);
		return {
			reload: answer.reload,
			tasks: answer.tasks
		}
	}

	static async deleteTask(event) {
		event.preventDefault();
		const task_id = event.target.querySelector('input[name="loaded_task_id"]').value;
		const user_id = Utils.getCookieValue('user_id');
		const answer = await Http.delete(`/api/task/${user_id}/${task_id}`);
		if (answer.error !== undefined) {
			Utils.renderError(answer.error);
		} else if (answer.status !== undefined) {
			Utils.renderMessage(answer.status);
			await App.updateTaskList(user_id);
		}
	}

	static async logout() {
		const user_id = Utils.getCookieValue('user_id');
		const answer = await Http.delete(`/api/auth/${user_id}`);
		if (answer.error !== undefined) Utils.renderError(answer.error);
		else if (answer.reload === true) App.run();
	}

	static async deleteUser() {
		const user_id = Utils.getCookieValue('user_id');
		const answer = await Http.delete(`/api/user/${user_id}`);
		if (answer.error !== undefined) Utils.renderError(answer.error);
		else if (answer.reload === true) App.run();
	}

	static run() {
		const user_id = Utils.getCookieValue('user_id');
		const user_name = Utils.getCookieValue('user_name');
		if (!user_id || !user_name) {
			this.renderLogin();
		} else {
			App.root.innerHTML = Html.userHeader(user_name) + Html.taskArea() + Html.footer();
			document.querySelector('#outBtn').addEventListener('click', App.logout);
			document.querySelector('#delBtn').addEventListener('click', App.deleteUser);
			document.querySelector('#createForm').addEventListener('submit', App.createTask);
			App.updateTaskList(user_id);
		}
	}
}

App.run();