class ErrorHandler {
	static array(errorsArray) {
      	var i, errorsReadable = "";
      	for (i = 0; i < errorsArray.length; i++) 
      	{
    		errorsReadable += errorsArray[i] + "<br>";
		}
		return errorsReadable;
	}
}
export default ErrorHandler;