export class Utils {
	static getCookieValue(cookieName) {
		const cookie = document.cookie.split('; ').find((row) => row.startsWith(cookieName + '='));
		return cookie ? cookie.split('=')[1] : null;
	}

	static renderMessage(message) {
		document.getElementById('messageArea').innerHTML = `<p class="w-fit text-2xl font-medium text-green-500 rounded-md mb-4 p-2 mx-auto bg-white">${message}</p>`;
	}

	static renderError(error) {
		document.getElementById('messageArea').innerHTML = `<p class="w-fit text-2xl font-medium text-orange-600 rounded-md mb-4 p-2 mx-auto bg-white">${error}</p>`;
	}
}