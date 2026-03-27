<script>
    $(document).ready(function() {
        let currentIndex = -1;
        let studentsData = [];

        $('#student_name').on('keydown', function(e) {
            if (e.key === "ArrowDown" || e.key === "ArrowUp" || e.key === "Enter") {
                e.preventDefault();
                navigateOptions(e);
            }
        });

        $('#student_name').on('keyup', function(e) {
            let query = $(this).val();
            if (e.key !== "ArrowDown" && e.key !== "ArrowUp" && e.key !== "Enter") {
                if (query.length > 0) {
                    $.ajax({
                        url: '<?= url('live-search-item') ?>',
                        method: 'POST',
                        data: {
                            customer_name: query,
                            csrf_token: $('input[name="csrf_token"]').val()
                        },
                        dataType: 'json',
                        success: function(response) {
                            let output = '';
                            studentsData = [];
                            if (response.status === 'success' && response.students.length > 0) {
                                response.students.forEach(function(student) {
                                    studentsData.push(student);
                                    output += '<li class="res search-item" role="option" data-id="' + student.id + '"> <a href="<?= url('register-student-in-class') ?>/' + student.id + '" class="color">' + student.name + ' - ' + student.phone + '</a></li>';
                                });
                            } else {
                                output = '<li class="res search-item color" role="option">چیزی یافت نشد</li>';
                            }
                            $('#backResponse').html(output).show();
                            currentIndex = -1;
                        },
                        error: function(xhr, status, error) {
                            $('#backResponse').html('<li class="res search-item color" role="option">خطایی رخ داد، لطفا دوباره امتحان کنید</li>').show();
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    $('#backResponse').hide();
                }
            }
        });

        function navigateOptions(e) {
            const items = $('#backResponse li');
            const container = $('#backResponse');
            if (e.key === "ArrowDown") {
                if (currentIndex < items.length - 1) {
                    currentIndex++;
                    items.removeClass('selected');
                    items.eq(currentIndex).addClass('selected');
                }
            } else if (e.key === "ArrowUp") {
                if (currentIndex > 0) {
                    currentIndex--;
                    items.removeClass('selected');
                    items.eq(currentIndex).addClass('selected');
                }
            } else if (e.key === "Enter") {
                if (currentIndex > -1) {
                    const selectedItem = items.eq(currentIndex);
                    const link = selectedItem.find('a').attr('href');
                    if (link) {
                        window.location.href = link;
                    }
                }
            }

            const selectedElement = items.eq(currentIndex);
            if (selectedElement.length > 0) {
                container.scrollTop(selectedElement.position().top + container.scrollTop() - container.height() / 2);
            }
        }

        $(document).on('click', '#backResponse li', function() {
            const studentName = $(this).text();
            const studentId = $(this).data('id');
            $('#student_name').val(studentName);
            $('#user_id').val(studentId).trigger('change');
            $('#backResponse').hide();
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('#student_name, #backResponse').length) {
                $('#backResponse').hide();
            }
        });

        $('#student_name').on('focus', function() {
            if ($(this).val().length > 0) {
                $('#backResponse').show();
            }
        });
    });
</script>