const checkRolePermission = (role_permissions = {}, roles = [], permissions = []) => {
	let result = false;

	Object.keys(role_permissions).forEach((key) => {
		if(roles.length > 0) {
			if(roles.indexOf(key) !== -1) {
				result = true;
			}
		}

		if(permissions.length > 0) {
			for(let i = 0; i < role_permissions[key].length; i++) {
				if(permissions.indexOf(role_permissions[key][i]) !== -1) {
					result = true;
				}
			}
		}
	});

	return result;
};

const validationErrors = (errors) => {
	const overflow_element = document.getElementsByClassName("overflow-y-auto");

	overflow_element[0].scrollTop = 0;

	const results = errors;

	Object.keys(results).map((key, index) => {
		const keyName = key.split(".");

		if(keyName.length > 1) {
			for(let i = 0; i < results[key].length; i++) {
				results[keyName[0]] = [];
				results[keyName[0]].push(results[key][i]);
			}
		}
	});

	return results;
};

const getFormData = (form) => {
	const formData = new FormData();

	Object.keys(form).map((key, index) => {
		if(Array.isArray(form[key])) {
			form[key].map((arrayValue, arrayIndex) => {
				formData.append(key+'[]', arrayValue);
			});
		} else {
			formData.append(key, form[key]);
		}
	});

	return formData;
};

const validateImageIsExist = (current_key, fields) => {
	const not_have_images = [];

	for(let i = 0; i <= current_key; i++) {
		if(i != current_key && typeof fields[i] === "undefined") {
			not_have_images.push(i);
		}
	}

	return not_have_images;
};

const alertNotHaveImage = (numbers, title, field) => {
	let string = "Warning\r\n\r\n"+title+" don't have image at number ";
	const total = numbers.length - 1;

	if(field) {
		document.getElementById(field).value = '';
	}

	for(let i = 0; i <= total; i++) {
		string += i + 1;

		if(i + 1 == total) {
			string += " & ";
		} else {
			if(i + 1 < total) {
				string += ", ";
			}
		}
	}

	alert(string);
};

const toBase64 = file => new Promise((resolve, reject) => {
	const reader = new FileReader();
	reader.readAsDataURL(file);
	reader.onload = () => resolve(reader.result);
	reader.onerror = reject;
});

const toDataURL = url => fetch(url)
	.then(response => response.blob())
	.then(blob => new Promise((resolve, reject) => {
	const reader = new FileReader()
	reader.onloadend = () => resolve(reader.result)
	reader.onerror = reject
	reader.readAsDataURL(blob)
}))

const dataURLtoFile = (dataurl, filename) => {
	var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
	bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
	
	while(n--){
		u8arr[n] = bstr.charCodeAt(n);
	}
	
	return new File([u8arr], filename, {type:mime});
}

export { checkRolePermission, validationErrors, getFormData, validateImageIsExist, alertNotHaveImage, toBase64, toDataURL, dataURLtoFile };
