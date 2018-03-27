import store from '../store.js'
class Auth {
	//Store tokens for authenticated user in localStorage
	static storeTokens(response) 
	{
        //Store Access Token
        const authToken = response.data;
        localStorage.setItem('access_token', authToken.access_token);

        //Store Expiry Time Token (milliseconds)
        var token_expiry = authToken.expires_in * 1000 + new Date().getTime();
        localStorage.setItem('token_expiry', token_expiry);

        //Update VueX State with User Object
        //store.commit('userLoggedIn', this.getUser());
    }

    //Delete Tokens in localStorage and reset VueX store state
	static removeSession() 
	{
    	localStorage.removeItem('access_token');
    	localStorage.removeItem('token_expiry');

    	store.commit('userLoggedOut');
    }

    //Set Authorization Header for HTTP requests
    static setAuthHeader() 
    {
	    window.axios.defaults.headers.common['Authorization'] = 'Bearer' + localStorage.getItem('access_token');
  	}

    //Return Authorization Header for HTTP requests
    static getAuthHeader() 
    {
	    return {
	      'Authorization': 'Bearer ' + localStorage.getItem('access_token')
	    }
  	}
		
	//Check if user is authenicated
	static isAuthenticated() 
	{
	    // Check whether the current time is past the access token's expiry time
	    let expiresAt = JSON.parse(localStorage.getItem('token_expiry'));
	    return new Date().getTime() < expiresAt;
	}

	// Check if user has admin role
	// static checkAdmin() 
	// {
	// 	if(this.isAuthenticated()) {
	// 		axios.post('/api/auth/checkAdmin').then((response) => { 
 //            	return response.data;
 //        	}).catch((error) => console.log(error));
	// 	}
	// 	else {return false;}
	// }

	//Method to check if only a few minutes are left before token expiry, if so, call the Refresh Token Method
	static checkTokenRefresh() 
	{
		//Do calculation comparing current time and expiry time
		var difference = localStorage.getItem('token_expiry') - new Date().getTime();
		
		//If difference is less than or equal to 5 minutes, call refreshToken method
		if (difference <= 300000) //Milliseconds
		{
			this.refreshToken();
		}	
	}
		
	//Refresh Token Method
	static refreshToken() 
	{
		axios.post('/api/auth/refresh').then((response) => { 
			//Store new tokens
            this.storeTokens(response);
        }).catch((error) => console.log(error));
		
	}
}

export default Auth;