var USQL = '';
var bulans = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
var haris = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
function setkeyboard(v) {
	var h = parseInt(v);
	if (h > 0) {
		document.getElementById('container').style.height = 'calc(100% - '+(parseInt(v)/2)+'px)';
		document.getElementById('splash').style.height = 'calc(100% - '+(parseInt(v)/2)+'px)';
	} else {
		document.getElementById('container').style.height = null;
		document.getElementById('splash').style.height = null;
	}
};
function getuniq(x) {
	var tms = new Date().getTime();
	var nid = Math.floor(Math.random() * (tms + Math.floor(Math.random() * 99))).toString();
	x = nid.substr(1,6);
	return x;
};
function doget(tag,keys,res) {
	var isusql = false;
	var url = API+'?tag='+tag;
	var i = 0;
	keys.forEach(function() {
		i++;
		url += '&k'+i+'='+keys[i-1];
		if (USQL != '') {
			if (keys[i-1] == USQL) {
				console.log('ada '+USQL);
				if (!isusql) {
					isusql = true;
					url += '&usql='+USQL;
				}
			}	
		}
	});
	$.ajax({
		type: 'GET',
		url: url,
		async: true,
		processData: false,
		contentType: false,
		success: function(html) {
			res(html.toString());
		},
		error: function (req,err) {
			res(err.toString());
		}
	});
};
function dopost(tag,keys,datas,callBack) {
	var isusql = false;
	var formData = new FormData();
	formData.append('tag',tag);
	var i = 0;
	keys.forEach(function() {
		i++;
		formData.append('k'+i,keys[(i-1)]);
		if (USQL != '') {
			if (keys[i-1] == USQL) {
				console.log('ada '+USQL);
				if (!isusql) {
					isusql = true;
					formData.append('usql',USQL);
				}
			}	
		}
	})
	if (datas != undefined) {
		i = 0;
		datas.forEach(function() {
			i++;
			formData.append('d'+i,datas[(i-1)]);
		});
	}
	$.ajax({
		type: 'POST',
		url: API,
		async: true,
		data : formData,
		processData: false,
		contentType: false,
		success: function(html) {
			callBack(html);
		},
		error: function (req,err) {
			callBack(err);
		}
	});
};
String.prototype.toProperCase = function () {
    return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};
function numberFormat(num) {
    if (num != undefined) {
		return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
	} else {
		return '-';
	}
};
function toNO(i) {
	if (i < 10) {i = '0' + i;}
	return i;
};
function formatDate(e) {
	var dt = new Date();
	if (e != undefined && e != null && e != '') {dt = new Date(e);}
	var thn = dt.getFullYear();
	var bln = toNO(dt.getMonth()+1);
	var tgl = toNO(dt.getDate());
	var hour = toNO(dt.getHours());
	var mnt = toNO(dt.getMinutes());
	var scn = toNO(dt.getSeconds());
	var str = thn+'-'+bln+'-'+tgl+' '+hour+':'+mnt+':'+scn;
	return str;
};
function terbilang(a){
	var bilangan = ['','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas'];

	// 1 - 11
	if(a < 12){
		var kalimat = bilangan[a];
	}
	// 12 - 19
	else if(a < 20){
		var kalimat = bilangan[a-10]+' Belas';
	}
	// 20 - 99
	else if(a < 100){
		var utama = a/10;
		var depan = parseInt(String(utama).substr(0,1));
		var belakang = a%10;
		var kalimat = bilangan[depan]+' Puluh '+bilangan[belakang];
	}
	// 100 - 199
	else if(a < 200){
		var kalimat = 'Seratus '+ terbilang(a - 100);
	}
	// 200 - 999
	else if(a < 1000){
		var utama = a/100;
		var depan = parseInt(String(utama).substr(0,1));
		var belakang = a%100;
		var kalimat = bilangan[depan] + ' Ratus '+ terbilang(belakang);
	}
	// 1,000 - 1,999
	else if(a < 2000){
		var kalimat = 'Seribu '+ terbilang(a - 1000);
	}
	// 2,000 - 9,999
	else if(a < 10000){
		var utama = a/1000;
		var depan = parseInt(String(utama).substr(0,1));
		var belakang = a%1000;
		var kalimat = bilangan[depan] + ' Ribu '+ terbilang(belakang);
	}
	// 10,000 - 99,999
	else if(a < 100000){
		var utama = a/100;
		var depan = parseInt(String(utama).substr(0,2));
		var belakang = a%1000;
		var kalimat = terbilang(depan) + ' Ribu '+ terbilang(belakang);
	}
	// 100,000 - 999,999
	else if(a < 1000000){
		var utama = a/1000;
		var depan = parseInt(String(utama).substr(0,3));
		var belakang = a%1000;
		var kalimat = terbilang(depan) + ' Ribu '+ terbilang(belakang);
	}
	// 1,000,000 - 	99,999,999
	else if(a < 100000000){
		var utama = a/1000000;
		var depan = parseInt(String(utama).substr(0,4));
		var belakang = a%1000000;
		var kalimat = terbilang(depan) + ' Juta '+ terbilang(belakang);
	}
	else if(a < 1000000000){
		var utama = a/1000000;
		var depan = parseInt(String(utama).substr(0,4));
		var belakang = a%1000000;
		var kalimat = terbilang(depan) + ' Juta '+ terbilang(belakang);
	}
	else if(a < 10000000000){
		var utama = a/1000000000;
		var depan = parseInt(String(utama).substr(0,1));
		var belakang = a%1000000000;
		var kalimat = terbilang(depan) + ' Milyar '+ terbilang(belakang);
	}
	else if(a < 100000000000){
		var utama = a/1000000000;
		var depan = parseInt(String(utama).substr(0,2));
		var belakang = a%1000000000;
		var kalimat = terbilang(depan) + ' Milyar '+ terbilang(belakang);
	}
	else if(a < 1000000000000){
		var utama = a/1000000000;
		var depan = parseInt(String(utama).substr(0,3));
		var belakang = a%1000000000;
		var kalimat = terbilang(depan) + ' Milyar '+ terbilang(belakang);
	}
	else if(a < 10000000000000){
		var utama = a/10000000000;
		var depan = parseInt(String(utama).substr(0,1));
		var belakang = a%10000000000;
		var kalimat = terbilang(depan) + ' Triliun '+ terbilang(belakang);
	}
	else if(a < 100000000000000){
		var utama = a/1000000000000;
		var depan = parseInt(String(utama).substr(0,2));
		var belakang = a%1000000000000;
		var kalimat = terbilang(depan) + ' Triliun '+ terbilang(belakang);
	}

	else if(a < 1000000000000000){
		var utama = a/1000000000000;
		var depan = parseInt(String(utama).substr(0,3));
		var belakang = a%1000000000000;
		var kalimat = terbilang(depan) + ' Triliun '+ terbilang(belakang);
	}

  else if(a < 10000000000000000){
		var utama = a/1000000000000000;
		var depan = parseInt(String(utama).substr(0,1));
		var belakang = a%1000000000000000;
		var kalimat = terbilang(depan) + ' Kuadriliun '+ terbilang(belakang);
	}

	var pisah = kalimat.split(' ');
	var full = [];
	for(var i=0;i<pisah.length;i++){
	 if(pisah[i] != ""){full.push(pisah[i]);}
	}
	return full.join(' ');
};
