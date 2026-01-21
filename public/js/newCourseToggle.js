(function(){
    var courseSelect = document.getElementById('course');
    var newWrapper = document.getElementById('newCourseWrapper');
    var newInput = document.getElementById('new_course');
    if (!courseSelect || !newWrapper || !newInput) return;
    function toggle() {
        if (courseSelect.value === '__new') {
            newWrapper.style.display = 'block';
            newInput.required = true;
        } else {
            newWrapper.style.display = 'none';
            newInput.required = false;
        }
    }
    courseSelect.addEventListener('change', toggle);
    toggle();
})();