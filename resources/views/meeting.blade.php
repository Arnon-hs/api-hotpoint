<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
            integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
    <title></title>
</head>
<body>
<table class="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">#</th>
        <th scope="col">Speaker</th>
        <th scope="col">User</th>
        <th scope="col">Meeting time</th>
        <th scope="col">Meeting status</th>
        <th scope="col">Confirm</th>
        <th scope="col">Delete</th>
    </tr>
    </thead>
    <tbody>
    @foreach($result as $action)
        <tr class="item-{{ $action->id }}">
            <th scope="row">{{ $action->id }}</th>
            <td>{{ $action->speaker()->speaker_fname }} {{ $action->speaker()->speaker_lname }}</td>
            <td>{{ $action->user()->fname }} {{ $action->user()->lname }} - {{ $action->user()->email }}</td>
            <td>{{ $action->meeting_time }}</td>
            <td>
                @if($action->meeting_confirm == 0)
                    Not confirmed
                @elseif($action->meeting_confirm == 1)
                    Confirmed
                @endif
            </td>
            <td>
                @if($action->meeting_confirm == 0)
                    <form data-id="{{ $action->id }}">
                        <button class="btn btn-sm btn-primary update mt-1" type="button"
                                onclick="myConfirm({{ $action->id }})">Confirm
                        </button>
                    </form>
                @endif
            </td>
            <td>
                <form data-id="{{ $action->id }}">
                    <button class="btn btn-sm btn-danger update mt-1" type="button"
                            onclick="myDelete({{ $action->id }})">Delete
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    function myConfirm(e) {
        let id = e;
        let action = 'confirm';
        let data = {
            id: id,
            action: action
        };

        $.ajax({
            url: "{{env('APP_URL').'/api/meeting/update'}}",
            type: 'post',
            data: data,
            success: function (response) {
                location.reload();
                console.log(response);
            }
        });
    }

    function myDelete(x) {
        let id = x;
        let action = 'delete';
        let data = {
            id: id,
            action: action
        };

        $.ajax({
            url: "{{env('APP_URL').'/api/meeting/delete'}}",
            type: 'post',
            data: data,
            success: function (response) {
                console.log(response);
                location.reload();
            }
        });
    }
</script>
</body>
</html>
