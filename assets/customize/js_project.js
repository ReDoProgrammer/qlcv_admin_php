$(document).ready(function () {
    $('.select2').select2();

    CKEDITOR.replace( 'description' );

    $('.summernote').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ol', 'ul', 'paragraph', 'height']],
            ['table', ['table']],
            ['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
        ]
    })


    $('.datepicker').datetimepicker({
        dateFormat: 'yy/mm/dd', // format of date
        timeFormat: 'HH:mm', // format of time
        showButtonPanel: true, // show button panel
        showTimezone: false, // hide timezone picker
        controlType: 'select', // control type for time
        oneLine: true // display in one line
    });

    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    // Lắng nghe sự kiện change trên start_date
    startDateInput.addEventListener('change', function () {
        // Tính toán giá trị mới cho end_date bằng cách thêm 8 giờ vào giá trị của start_date
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(startDate.getTime() + 8 * 60 * 60 * 1000);
        const endDateISOString = endDate.toISOString().slice(0, 16);

        // Cập nhật giá trị của end_date
        endDateInput.value = endDateISOString;
    });


    $('#company_select').change(function () {
        var company_id = $(this).val();
        $.ajax({
            url: 'get_customers.php',
            type: 'GET',
            data: {
                company_id: company_id
            },
            dataType: 'json',
            success: function (data) {
                // Xóa danh sách khách hàng cũ
                $('#customer_select').empty();
                // Thêm danh sách khách hàng mới
                $.each(data, function (index, customer) {
                    $('#customer_select').append('<option value="' + customer.id + '">' + customer.name + '</option>');
                });
            },
            error: function () {
                // Hiển thị thông báo nếu có lỗi xảy ra
                alert('An error occurred while fetching customers.');
            }
        });
    });
})

$('#manage-project').submit(function (e) {
    e.preventDefault()
    start_load()
    $.ajax({
        url: 'ajax.php?action=save_project',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function (resp) {
            if (resp == 1) {
                alert_toast('Lưu dữ liệu thành công', "success");
                setTimeout(function () {
                    location.href = 'index.php?page=project_list'
                }, 2000)
            }
        }
    })
})