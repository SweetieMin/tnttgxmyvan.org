document.addEventListener("DOMContentLoaded", function () {
    const message = `%cDừng lại!
%cĐây là công cụ dành cho nhà phát triển...`;

    const style1 = "font-size: 30px; color: red; font-weight: bold;";
    const style2 = "font-size: 16px;";

    setTimeout(() => {
        if (window.console) {
            console.log(message, style1, style2);
        }
    }, 1000);
});
