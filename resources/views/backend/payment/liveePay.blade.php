<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div class="d-flex justify-content-center mt-5">
        <form  action="https://www.livees.net/Checkout/api4" method="POST"  class="d-flex flex-column gap-3">

            <input type="hidden" name="_" value="sa4b4km6c0l9eq7y6od88cnjp62efvr6ix59u5taz2ghw0193" class="form-control">
            <input type="hidden" name="__" value="bj65bih1kzo740snwbru2q9px3v5503fetfdaaegmc64yle58" class="form-control">
            <input type="hidden" name=" postURL" value="{{url('/success')}}" class="form-control">
            <input type="hidden" name="amt2" value="100.55" class="form-control">
            <input type="hidden" name="currency" value="BOB" class="form-control">
            <input type="hidden" name="invno" value="   " class="form-control">
            <input type="text" name="name" placeholder="Enter First Name" class="form-control">
            <input type="text" name=" lastname" placeholder="Enter lastname" class="form-control">
            <input type="email" name="email" value="{{Auth::user()->email}}" class="form-control">
            <select name="pais" class="form-control">
            <option value="BO">Bolivia</option>
            <option value="US">Estados Unidos</option>
            </select>
            <input type="text" name="ciudad" value="Santa Cruz de la Sierra" class="form-control">
            <select name="estado_lbl" class="form-select">
            <option value="La Paz">La Paz</option>
            <option value="Santa Cruz">Santa Cruz</option>
                <input type="submit" class="btn btn-primary" value="submit">
            </form>
    </div>
</body>
</html>
