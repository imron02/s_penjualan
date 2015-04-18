
function aturTinggi()
{
	document.getElementsByClassName('konten')[0].style.minHeight = window.innerHeight + 'px';
}



window.onload = function()
{
	aturTinggi();	
	var subJudul = document.getElementsByClassName('sub-judul')[0];
	subJudul.innerHTML = '~&raquo; '+subJudul.innerHTML+' &laquo;~';
	window.onresize = function()
	{
		aturTinggi();	
	}
}



