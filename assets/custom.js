import Swal from 'sweetalert2'

$(document).ready(function () {
    $(".add-address-btn").on('click', function () {
        let address_container = $(".addresses-container")
        let counter = parseInt(address_container.attr('data-address'))
        counter++;
        address_container.attr('data-address', counter)
        let html = `<div class="row g-3 address-row mb-2">
                                    <div class="col">
                                        <input type="text" name="address[${counter}][street]" class="form-control" placeholder="Street" required>
                                    </div>
                                    <div class="col">
                                        <input type="number" name="address[${counter}][zip]" class="form-control" placeholder="Zip" required>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="address[${counter}][city]" class="form-control" placeholder="City" required>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="address[${counter}][country]" class="form-control" placeholder="County" required>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-danger delete-address-btn">Delete</button>
                                    </div>
                                </div>`;

        address_container.append(html)
    });
    $(document).on("click", ".delete-address-btn", function () {
        let obj = $(this);
        let len = $(".address-row").length;
        let delete_url = obj.attr('data-delete-url');
        if (delete_url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this address?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: delete_url,
                        type: 'DELETE',
                        success: function (result) {
                            console.log(result)
                            Swal.fire(
                                'Deleted!',
                                result.status,
                                'success'
                            );
                            obj.closest(".address-row").remove();
                        }
                    });
                }
            })
        } else if (len > 1) {
            $(this).closest(".address-row").remove();
        }
    });
//    Phone Number
    $(".add-phone-btn").on('click', function () {
        let phone_container = $(".phone-container")
        let counter = parseInt(phone_container.attr('data-phone'))
        counter++;
        phone_container.attr('data-phone', counter)
        let html = `<div class="row g-3 phone-row mb-2">
                                    <div class="col">
                                        <input type="text" name="phone[${counter}][number]" class="form-control"
                                               placeholder="Phone Number" required>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-danger delete-phone-btn">Delete</button>
                                    </div>
                                </div>`;

        phone_container.append(html)
    });
    $(document).on("click", ".delete-phone-btn", function () {
        let obj = $(this);
        let len = $(".phone-row").length;
        let delete_url = obj.attr('data-delete-url');
        if (delete_url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this phone number?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: delete_url,
                        type: 'DELETE',
                        success: function (result) {
                            console.log(result)
                            Swal.fire(
                                'Deleted!',
                                result.status,
                                'success'
                            );
                            obj.closest(".phone-row").remove();
                        }
                    });
                }
            })
        } else if (len > 1) {
            obj.closest(".phone-row").remove();
        }
    });
    // Update Address
    $(document).on("click", ".update-address-btn", function () {
        let parent = $(this).closest('.address-row')
        let url = $(this).attr('data-edit-url')
        let data = {};
        data.street = parent.find('.street').val()
        data.zip = parent.find('.zip').val()
        data.city = parent.find('.city').val()
        data.country = parent.find('.country').val()
        if (!data.street || !data.zip || !data.city || !data.country) {
            Swal.fire({
                title: 'Error!',
                text: 'Please fill all required fields.',
                icon: 'error',
                confirmButtonText: 'OK'
            })
            return false;
        }
        $.post(url, data, function (body, status, res) {
            console.log(body)
            if (res.status === 200) {
                Swal.fire('Updated', body.status, 'success')
            }
        }).fail(function (res) {
            Swal.fire({
                title: 'Error!',
                text: res.responseJSON.status,
                icon: 'error',
                confirmButtonText: 'OK'
            })
        });
    });
    // Update Phone
    $(document).on("click", ".update-phone-btn", function () {
        let parent = $(this).closest('.phone-row')
        let url = $(this).attr('data-edit-url')
        let data = {};
        data.phone_no = parent.find('.phone_no').val()
        if (!data.phone_no) {
            Swal.fire({
                title: 'Error!',
                text: 'Please fill all required fields.',
                icon: 'error',
                confirmButtonText: 'OK'
            })
            return false;
        }
        $.post(url, data, function (body, status, res) {
            console.log(body)
            if (res.status === 200) {
                Swal.fire('Updated', body.status, 'success')
            }
        }).fail(function (res) {
            Swal.fire({
                title: 'Error!',
                text: res.responseJSON.status,
                icon: 'error',
                confirmButtonText: 'OK'
            })
        });
    });
// Delete User

    $(document).on("click", ".delete-user-btn", function () {
        let obj = $(this);
        let delete_url = obj.attr('data-delete-url');
        if (delete_url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this User?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: delete_url,
                        type: 'DELETE',
                        success: function (result) {
                            Swal.fire(
                                'Deleted!',
                                result.status,
                                'success'
                            );
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        }
                    });
                }
            })
        } else if (len > 1) {
            obj.closest(".phone-row").remove();
        }
    });


});