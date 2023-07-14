<style>
    .form-control {
        border-style: solid;
        border-width: 1px;
        border-image: linear-gradient(45deg, rgb(0, 143, 104), rgb(250, 224, 66)) 1;
    }

    .form-control:focus {
        border-width: 2px;
        border-image: linear-gradient(to right, blue, darkred) 1;
    }

    .btn-grad {
        background-image: linear-gradient(to right, #314755 0%, #26a0da 51%, #314755 100%);
        text-align: center;
        text-transform: uppercase;
        transition: 0.5s;
        background-size: 200% auto;
        color: white;
        box-shadow: 0 0 20px #eee;
        display: block;
    }

    .btn-grad:hover {
        background-position: right center; /* change the direction of the change here */
        color: #fff;
    }

    .box{
        box-shadow: 2px 2px 5px gray, -2px -2px 5px gray !important;
    }

    #toast-container > div{
        opacity: 1 !important;
    }

    .select2-container--default .select2-selection--single {
        border-radius: 0px !important;
        border-image: linear-gradient(45deg, rgb(0, 143, 104), rgb(250, 224, 66)) 1;
        height: 34px !important;
    }

    .select2-container--default .select2-selection--single:focus {
        border-width: 2px;
        border-image: linear-gradient(to right, blue, darkred) 1;
    }

    .error {
        color: red;
    }
</style>