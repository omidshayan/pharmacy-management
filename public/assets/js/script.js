const sidebar = document.querySelector(".sidebar");
const hamber = document.querySelector(".hamber");
const mainContent = document.querySelector(".content");
const appbar = document.querySelector(".appbar");
const menuToggle = document.querySelector("#menu-toggle");
const left_menu_active = document.querySelector("#left-menu-active");
const settings = document.querySelector(".temp-settings");
const setting = document.querySelector(".setting");
const closeLeftMenu = document.querySelector(".closeLeftMenu");

// events
const shwo_msg_events = document.querySelector(".show-msg-events");

function closeMenu() {
  if (shwo_msg_events) {
    shwo_msg_events.classList.remove("active");
  }
}

// Close menu when clicking outside the menu
document.addEventListener("click", function (event) {
  const targetElement = event.target;

  if (
    !targetElement.closest(".show-msg-events") &&
    !targetElement.closest(".show-msg")
  ) {
    closeMenu();
  }
});

hamber.addEventListener("click", function () {
  sidebar.classList.toggle("active");
  mainContent.classList.toggle("active");
  appbar.classList.toggle("active");
  menuToggle.classList.toggle("active");
});

menuToggle.addEventListener("click", function () {
  sidebar.classList.toggle("active");
  mainContent.classList.toggle("active");
  appbar.classList.toggle("active");
  menuToggle.classList.toggle("active");
});

// left menu
left_menu_active.addEventListener("click", function () {
  left_menu_active.classList.toggle("left-menu-active");
  setting.classList.toggle("active-left-menu");
});

settings.addEventListener("click", function () {
  setting.classList.toggle("active-left-menu");
  left_menu_active.classList.toggle("left-menu-active");
});
closeLeftMenu.addEventListener("click", function () {
  setting.classList.toggle("active-left-menu");
  left_menu_active.classList.toggle("left-menu-active");
});

// Sidebar submenu
$(".sidebar-dropdown-menu").slideUp("fast");
$(
  ".sidebar-menu-item.has-dropdown > a, .sidebar-dropdown-menu-item.has-dropdown > a",
).click(function (e) {
  e.preventDefault();

  if (!$(this).parent().hasClass("focused")) {
    $(this).parent().parent().find(".sidebar-dropdown-menu").slideUp("fast");
    $(this).parent().parent().find(".has-dropdown").removeClass("focused");
  }
  $(this).next().slideToggle("fast");
  $(this).parent().toggleClass("focused");
});

$(".sidebar-toggle").click(function () {
  $(".sidebar").toggleClass("collapsed");

  $(".sidebar.collapsed").mouseleave(function () {
    $(".sidebar-dropdown-menu").slideUp("fast");
    $(
      ".sidebar-menu-item.has-dropdown, .sidebar-dropdown-menu-item.has-dropdown",
    ).removeClass("focused");
  });
});

$(".sidebar-overlay").click(function () {
  $(".sidebar").addClass("collapsed");

  $(".sidebar-dropdown-menu").slideUp("fast");
  $(
    ".sidebar-menu-item.has-dropdown, .sidebar-dropdown-menu-item.has-dropdown",
  ).removeClass("focused");
});

if (window.innerWidth < 768) {
  $(".sidebar").addClass("collapsed");
}

// dark and light mode
document.addEventListener("DOMContentLoaded", function () {
  let darkModeEnabled = localStorage.getItem("darkModeEnabled");
  if (darkModeEnabled === "true") {
    document.body.classList.add("light-mode");
  }

  // start input expand
  const inputs = document.querySelectorAll(".auto-expand");
  inputs.forEach((input) => {
    const baseWidth = parseInt(input.dataset.defaultWidth) || 50;

    const adjustWidth = () => {
      const newWidth = (input.value.length + 1) * 10;
      input.style.width = Math.max(baseWidth, newWidth) + "px";
    };

    adjustWidth();

    input.addEventListener("input", adjustWidth);
  });
  // end intpu expand
});
function toggleDarkMode() {
  let body = document.body;
  body.classList.toggle("light-mode");
  let darkModeEnabled = body.classList.contains("light-mode");
  localStorage.setItem("darkModeEnabled", darkModeEnabled);
}

// collapse
const accor = document.querySelectorAll(".accordion-title");
accor.forEach((item) => {
  item.addEventListener("click", function () {
    item.classList.toggle("active");
    const content = item.nextElementSibling;
    if (content.style.height) {
      content.style.height = null;
    } else {
      content.style.height = content.scrollHeight + "px";
    }
  });
});

const test = document.querySelectorAll(".accordion-test");
test.forEach((item) => {
  item.addEventListener("click", function () {
    item.classList.toggle("active");
    const content = item.nextElementSibling;
    if (content.style.height) {
      content.style.height = null;
    } else {
      content.style.height = content.scrollHeight + "px";
    }
  });
});

// preview and check img size
const inputElement = document.getElementById("image");
inputElement.addEventListener("change", function (event) {
  const file = event.target.files[0];
  if (file) {
    if (file.size > 1024 * 1024) {
      alert("حجم عکس نباید بیشتر از ۱ مگابایت باشد.");
      inputElement.value = "";
      return;
    }
    const reader = new FileReader();
    reader.onload = function (e) {
      const imageUrl = e.target.result;
      const imagePreview = document.getElementById("imagePreview");
      imagePreview.innerHTML = `<img src="${imageUrl}" class="img" alt="">`;
    };
    reader.readAsDataURL(file);
  }
});

// enable checkbox
document.addEventListener("DOMContentLoaded", function () {
  const checkbox = document.getElementById("start");
  const dateTimeInput = document.getElementById("time_start");

  function toggleDateTimeInput() {
    if (checkbox.checked) {
      dateTimeInput.removeAttribute("disabled");
      dateTimeInput.classList.remove("grayed-out");
    } else {
      dateTimeInput.setAttribute("disabled", "disabled");
      dateTimeInput.classList.add("grayed-out");
    }
  }

  toggleDateTimeInput();

  checkbox.addEventListener("change", toggleDateTimeInput);
});
document.addEventListener("DOMContentLoaded", function () {
  const checkbox = document.getElementById("end");
  const dateTimeInput = document.getElementById("time_end");

  function toggleDateTimeInput() {
    if (checkbox.checked) {
      dateTimeInput.removeAttribute("disabled");
      dateTimeInput.classList.remove("grayed-out");
    } else {
      dateTimeInput.setAttribute("disabled", "disabled");
      dateTimeInput.classList.add("grayed-out");
    }
  }

  toggleDateTimeInput();

  checkbox.addEventListener("change", toggleDateTimeInput);
});

// select inputs age
document.getElementById("age").value;
function toggleInput() {
  var ageInput = document.getElementById("age");
  var birthdayInput = document.getElementById("birthday");
  var selectedInputType = document.querySelector(
    'input[name="inputType"]:checked',
  ).value;
  if (selectedInputType === "age") {
    ageInput.disabled = false;
    birthdayInput.disabled = true;
  } else {
    ageInput.disabled = true;
    birthdayInput.disabled = false;
  }
}
