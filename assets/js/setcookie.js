const checkBox = document.querySelector('#savelogin');


//


function readUnescapedCookie(cookieName) {
    let cookieValue = document.cookie;
    let cookieRegExp = new RegExp("\\b" + cookieName
        + "=([^;]*)");

    cookieValue = cookieRegExp.exec(cookieValue);


    if (cookieValue != null) {
        cookieValue = cookieValue[1];
    }

    return cookieValue;
};



//

const phpold = readUnescapedCookie('PHPSESSID');
document.cookie = "PHPSESSID=" + phpold + "; expires=session";
console.log('logout when session end');

//

checkBox.addEventListener('change', function () {


    if (checkBox.checked) {
        console.log('i do not logout for 30 day');
    	date = new Date();
	date.setDate(date.getDate() + 30);
        const phpold = readUnescapedCookie('PHPSESSID');
        document.cookie = "PHPSESSID=" + phpold + "; expires=" + date;
    } else {

        const phpold = readUnescapedCookie('PHPSESSID');
        document.cookie = "PHPSESSID=" + phpold + "; expires=session";
        console.log('logout when session end');

    }
});


