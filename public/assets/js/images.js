//After every elements have been load in this window, this code will be executed
window.onload= () => {
    // Handle "delete" buttons
    let links = document.querySelectorAll("[data-delete]")
    
    //Loop on links
    for (let link of links) {
        link.addEventListener("click", function(e){
            //Prevent navigation
            e.preventDefault()

            //ask confirm
         if(confirm("voulez-vous supprimer cette image ? ")){
                //Send Ajax request to link's href with DELETE method
                //all of this referes to ($this->isCsrfTokenValid('delete'.$artistImage->getId(), $data['_token'])) in the Controller

                //Get the URL from the href
                fetch(this.getAttribute("href"),{
                    //Send DELETE method
                    method: 'DELETE',
                    //Send the token
                    headers: {
                        //Indicate to server that request come from AJAX request
                        "X-Requested-With" : "XMLHttpRequest",
                        //type of the sent content: JSON
                        "Content-Type": "application/json"
                    },
                    //Send JSON data in this request: search in this href every attribute starting with data and containing token
                    body: JSON.stringify ({"_token": this.dataset.token})  
                }).then(
                    //Get JSON response
                    response => response.json()
                ).then(
                    //Response is success or error
                    data => {
                        if(data.success)
                            this.parentElement.remove()
                        else
                            alert(data.error)
                })
         }
        })
    }
        
}