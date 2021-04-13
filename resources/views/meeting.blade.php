<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title></title>
</head>
<body>
<table class="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">#</th>
        <th scope="col">Speaker name</th>
        <th scope="col">Meeting time</th>
        <th scope="col">Meeting status</th>
        <th scope="col">Changer</th>
    </tr>
    </thead>
    <tbody>
    @foreach($result as $action)
        <tr class="item-{{ $action->id }}">
            <th scope="row">{{ $action->id }}</th>
            <td>{{ $action->speaker()->speaker_fname }}</td>
            <td>{{ $action->meeting_time }}</td>
            <td>{{ $action->meeting_confirm }}</td>
            <td>
                <form method="POST" data-id="{{ $action->id }}">
{{--                    <input type="hidden" value="{{ $action->id }}" name="id" id="id">--}}
                    <button class="btn btn-sm btn-primary update mt-1" type="submit" name="confirm" id="confirm-{{ $action->id }}" value="confirm">Confirm</button>
                    <button class="btn btn-sm btn-alert update mt-1" type="submit" name="delete" id="delete-{{ $action->id }}" value="delete">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    $(document).ready( () => {
        $('form').submit(function(e) {
            e.preventDefault();

            let id = $(this).data('id');
            let action_confirm = $('#confirm'+id).val();
            let data = {
                confirm: action_confirm,
                id: id
            };
            console.log(data);
            {{--if(action_confirm !== ''){--}}
            {{--    $.ajax({--}}
            {{--        url: "{{env('APP_URL').'update'}}",--}}
            {{--        type: 'post',--}}
            {{--        data: data,--}}
            {{--        success: function(response) {--}}
            {{--            console.log(response);--}}
            {{--        }--}}
            {{--    });--}}
            {{--}else{--}}
            {{--    alert('ERRORchik!');--}}
            {{--}--}}
        });
    });
</script>
</body>
</html>
