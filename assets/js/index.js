jQuery( document ).ready(function($) {

	const showLoading = () => {
		Swal.fire({
			text: 'Upload in progress. Please wait for a while...',
			timerProgressBar: true,
			allowOutsideClick: false,
			didOpen: () => {
			  Swal.showLoading()
			}
		});
	}

	const searching = () => {
		Swal.fire({
			text: 'Searching in progress. Please wait for a while...',
			timerProgressBar: true,
			allowOutsideClick: false,
			didOpen: () => {
			  Swal.showLoading()
			}
		});
	}
	jQuery( '.table-result-stock' ).hide();

	jQuery( '#select-stock' ).on( 'change', function(e) {
		let selected = jQuery(this).val();
		if( selected ) {
			searching();
			jQuery( '.table-view-stock' ).hide();
			jQuery.ajax({
				url: 'actions/saving.php', 
				type: 'POST',
				dataType: 'json', 
				data: {
					data : selected,
					search : 'single',
				},
				success : (msg) => {
					numbers = msg.numbers;
					jQuery( '.table-result-stock' ).show();
					jQuery( '.table-view-stock' ).show();
					jQuery( '.table-view-stock' ).html(msg.history);
					jQuery( '.table-result-stock' ).html( '<div class="col-xl-3 col-sm-6 grid-margin stretch-card"><div class="card"><div class="card-body"><div class="row"><div class="col-9"><div class="d-flex align-items-center align-self-start"><h3 class="mb-0">$'+ numbers.today_profit.toFixed(2) +'</h3><p class="text-success ms-2 mb-0 font-weight-medium"></p></div></div><div class="col-3"><div class="icon icon-box-success"><span class="mdi mdi-arrow-top-right icon-item"></span></div></div></div><h6 class="text-muted font-weight-normal">Revenue per stock</h6></div></div></div><div class="col-xl-3 col-sm-6 grid-margin stretch-card"><div class="card"><div class="card-body"><div class="row"><div class="col-9"><div class="d-flex align-items-center align-self-start"><h3 class="mb-0">$'+numbers.highest_price+'</h3><p class="text-success ms-2 mb-0 font-weight-medium"</p></div></div><div class="col-3"><div class="icon icon-box-success"><span class="mdi mdi-arrow-top-right icon-item"></span></div></div></div><h6 class="text-muted font-weight-normal">Highest Income</h6></div></div></div><div class="col-xl-3 col-sm-6 grid-margin stretch-card"><div class="card"><div class="card-body"><div class="row"><div class="col-9"><div class="d-flex align-items-center align-self-start"><h3 class="mb-0">$'+numbers.mean_price.toFixed(2)+'</h3><p class="text-success ms-2 mb-0 font-weight-medium"></p></div></div><div class="col-3"><div class="icon icon-box-success"><span class="mdi mdi-arrow-top-right icon-item"></span></div></div></div><h6 class="text-muted font-weight-normal">Mean Price </h6></div></div></div><div class="col-xl-3 col-sm-6 grid-margin stretch-card"><div class="card"><div class="card-body"><div class="row"><div class="col-9"><div class="d-flex align-items-center align-self-start"><h3 class="mb-0">$'+numbers.standard_deviation.toFixed(2)+'</h3><p class="text-success ms-2 mb-0 font-weight-medium"></p></div></div><div class="col-3"><div class="icon icon-box-success"><span class="mdi mdi-arrow-top-right icon-item"></span></div></div></div><h6 class="text-muted font-weight-normal">Standard Deviation </h6></div></div></div>' );					
					swal.close();
				}
			})
		} else {
			jQuery( '.table-view-stock' ).show();
		}
	});

    jQuery( '.upload-stock-form' ).on( 'submit', function(e) {
        e.preventDefault();
        let file   = jQuery( ".upload-stock" ).prop( 'files' );
        let upload = new FormData(); 
		upload.append( "stock", file[0] ); 
		upload.append( "action", "perform_upload" );
		showLoading();
		jQuery.ajax({ 
			url: 'actions/upload.php', 
			type: 'POST', 
			processData: false, 
			contentType: false, 
			dataType: 'json', 
			data: upload ,
			success: function( response ) {
				switch (response.status) {
					case '200':
					case 200:
						Swal.fire({
							text: response.text,
							timerProgressBar: true,
							allowOutsideClick: false,
							didOpen: () => {
							  Swal.showLoading()
							}
						});
						asyncCall( response );
						break;
					default:
						Swal.fire({
							icon: 'error',
							title: response.text,
							text: response.errors,
						}).then( () => {
							window.location.reload();
						});
						break;
				}
            }
		});
    });

	function doSingleSync( response ) {
		return new Promise( ( resolve, reject ) => {
			jQuery.ajax({
				url: 'actions/saving.php', 
				type: 'POST',
				dataType: 'json', 
				data: {
					data : response.data
				},
				success : (msg) => {
					resolve( msg );
				}
			})
		});
	}
	  
	async function asyncCall( response ) {
		let result = await doSingleSync( response );
		if( result.count != 0 ) {
			asyncCall(result);
		} else {
			Swal.fire({
				text: 'Upload completed!!',
				icon : 'success'
			});	
		}
	}
});