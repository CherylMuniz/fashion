// JavaScript Document
function StartUp() {
	
	new Protofade('protofade', { duration: 2, delay:5.0});
}
 
document.observe ('dom:loaded', StartUp);