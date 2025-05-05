export class Html {
	static basicHeader() {
		return `
			<header class="flex justify-between items-center w-full bg-zinc-700 mt-4 rounded-2xl px-12 py-4">
				<span class="inline-block mx-4 text-5xl text-white font-medium">
					The new "Tasker"&copy;
				</span>
				<div>
					<ul class="flex">
						<li>
							<button id="logBtn" class="inline-block px-6 py-2 bg-blue-600 transition duration-250 hover:bg-blue-800 text-3xl text-white font-medium rounded-lg mx-4">Login</button>
						</li>
						<li>
							<button id="regBtn" class="inline-block px-6 py-2 bg-yellow-300 transition duration-250 hover:bg-yellow-600 text-3xl text-black font-medium rounded-lg mx-4">Registration</button>
						</li>
					</ul>
				</div>
			</header>
		`;
	}

	static userHeader(user) {
		return `
			<header class="flex justify-between items-center w-full bg-zinc-700 mt-4 rounded-2xl px-12 py-4">
				<span class="inline-block mx-4 text-5xl text-white font-medium">
					The new "Tasker"&copy;
				</span>
				<div>
					<ul class="flex">
						<li>
							<button class="inline-block px-6 py-2 bg-green-600 text-3xl text-white font-medium rounded-lg mx-4">${user}</button>
						</li>
						<li>
							<button id="outBtn" class="inline-block px-6 py-2 bg-yellow-300 transition duration-250 hover:bg-yellow-600 text-3xl text-black font-medium rounded-lg mx-4">Logout</button>
						</li>
						<li>
							<button id="delBtn" class="inline-block px-6 py-2 bg-red-500 transition duration-250 hover:bg-red-700 text-3xl text-black font-medium rounded-lg mx-4">Delete</button>
						</li>
					</ul>
				</div>
			</header>
		`;
	}

	static footer() {
		return `
			<footer class="w-full bg-zinc-700 my-6 rounded-2xl px-12 py-1 flex flex-[0_0_auto]">
				<div class="flex justify-between w-full items-center">
					<span class="inline-block">
						<a href="https://github.com/TimurDev-hub" title="GitHub" target="_blank" class="inline-block w-16 h-16 mr-1 bg-[url(/app/client/imgs/github.svg)] bg-cover"></a>
						<a href="https://x.com" title="Twitter" target="_blank" class="inline-block w-16 h-16 bg-[url(/app/client/imgs/twitter.svg)] bg-cover"></a>
					</span>
					<p title="Creator" class="inline-block mx-4 text-3xl text-white font-medium">Created by Timur &copy;</p>
				</div>
			</footer>
		`;
	}

	static regForm() {
		return `
			<div class="bg-blue-300 w-fit rounded-md mt-20 mx-auto">
				<form autocomplete="on" accept-charset="UTF-8">
					<fieldset class="flex flex-col">
						<legend class="text-white mx-auto inline-block h-fit bg-blue-700 rounded-t-md w-full text-center text-5xl font-medium p-4 mb-8">Registration</legend>
						<input class="bg-white mx-12 rounded-md px-4 py-2 w-100 text-3xl mb-6 outline-none" type="text" name="user_name" placeholder="Your username..." maxlength="16" required>
						<input class="bg-white mx-12 rounded-md px-4 py-2 w-100 text-3xl mb-10 outline-none" type="password" name="user_password" placeholder="Your password..." maxlength="16" required>
						<input class="bg-green-500 transition duration-250 hover:bg-green-600 rounded-md p-2 text-4xl font-medium w-80 mx-auto mb-10" type="submit" value="Create">
						<div id="messageArea"></div>
					</fieldset>
				</form>
			</div>
			<div class="flex flex-[1_0_auto]"></div>
		`;
	}

	static logForm() {
		return `
			<div class="bg-blue-300 w-fit rounded-md mt-20 mx-auto">
				<form autocomplete="on" accept-charset="UTF-8">
					<fieldset class="flex flex-col">
						<legend class="text-white mx-auto inline-block h-fit bg-blue-700 rounded-t-md w-full text-center text-5xl font-medium p-4 mb-8">Log in account</legend>
						<input class="bg-white mx-12 rounded-md px-4 py-2 w-100 text-3xl mb-6 outline-none" type="text" name="user_name" placeholder="Your username..." maxlength="16" required>
						<input class="bg-white mx-12 rounded-md px-4 py-2 w-100 text-3xl mb-10 outline-none" type="password" name="user_password" placeholder="Your password..." maxlength="16" required>
						<input class="bg-green-500 transition duration-250 hover:bg-green-600 rounded-md p-2 text-4xl font-medium w-80 mx-auto mb-10" type="submit" value="Enter">
						<div id="messageArea"></div>
					</fieldset>
				</form>
			</div>
			<div class="flex flex-[1_0_auto]"></div>
		`;
	}

	static createArea() {
		return `
			<div class="bg-blue-300 w-fit rounded-md mt-20 mx-auto">
				<form autocomplete="off" accept-charset="UTF-8">
					<fieldset class="flex flex-col">
						<legend class="text-white mx-auto inline-block h-fit bg-blue-700 rounded-t-md w-full text-center text-5xl font-medium p-4 mb-8">Create task</legend>
						<input class="bg-white mx-12 rounded-md px-4 py-2 w-100 text-3xl mb-6 outline-none" type="text" name="task_title" placeholder="Title..." maxlength="16" required>
						<textarea resize-none class="bg-white mx-12 rounded-md px-4 py-2 w-100 text-3xl mb-10 outline-none" name="task_content" placeholder="Main text..." maxlength="100" required rows=3></textarea>
						<input class="bg-green-500 transition duration-250 hover:bg-green-600 rounded-md p-2 text-4xl font-medium w-80 mx-auto mb-10" type="submit" value="Create">
						<div id="messageArea"></div>
					</fieldset>
				</form>
			</div>
			<div class="flex flex-[1_0_auto]"></div>
		`;
	}

	static tasksArea() {
		return ``;
	}

	static task() {
		return ``;
	}
}