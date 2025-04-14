import { Html } from "./html.js";
import { Http } from "./http.js";

const root = document.getElementById('root');

function renderLogin() {
	root.innerHTML = Html.basicHeader() + Html.logForm() + Html.footer();
}

function renderRegister() {
	root.innerHTML = Html.basicHeader() + Html.regForm() + Html.footer();
}

renderLogin();

root.addEventListener('click', (event) => {
	if (event.target.id === 'regBtn') {
		renderRegister();
	} else if (event.target.id === 'logBtn') {
		renderLogin();
	}
});