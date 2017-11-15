<!DOCTYPE html>
<html lang="en">
<head>
	<title>Conversor números romanos</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	
	<link href="./css/bootstrap.min.css" rel="stylesheet" />
	<link href="./css/bootstrap-grid.min.css" rel="stylesheet" />
	<link href="./css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	
	<style>
		.center
		 {
			 float: none;
			 margin-left: auto;
			 margin-right: auto;
		 }
        
        .col-md-2
        {
            padding: 0 !important;
            margin-left: 15px !important;
        }
	</style>
	
</head>

<body>
	<article class="container">
		<div class="center col-md-10">
            <h1 class="center">Conversor de números romanos</h1>
            <hr />
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-info">Para converter de romano para decimal digite os algarismos, para converter de decimal para romano digite o número.</div>
    
                    <form id="conversor" class="col-md-10">
                        <div class="form-group row">
                            <div classs="col-md-2">
                                <input class="form-control input-sm d" id="number" name="number" type="text" placeholder="Entrada" />
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-default">Converter </button>
                            </div>
                            <div classs="col-md-2">
                                <input class="form-control input-sm" id="result" name="result" type="text" placeholder="Resultado" disabled />
                            </div>
                        </div>
                        <input type="hidden" id="method" name="method" />
                    </form>
                    <div id="notify" class="alert alert-success d-none"></div>
                    <div id="error" class="alert alert-danger d-none"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8" style="padding: 0">
                    <table id="romToDec" class="display table table-hover table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <td>Romano</td>
                                <td>Decimal</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
		</div>
	</article>
	
	<script src="./js/jquery-3.2.1.min.js"></script>
	<script src="./js/popper.min.js"></script>
	<script src="./js/bootstrap.min.js"></script>
	<script src="./js/bootstrap.bundle.min.js"></script>
	<script src="./js/datatables.min.js"></script>
	<script src="./js/dataTables.bootstrap4.min.js"></script>
    <script src="./js/appFuncs.js"></script>
</body>

</html>
