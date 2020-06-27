export default class Gate{

    constructor(user){
        this.user = user;
    }

    isAdmin(){
        return this.user.role_id === 1;
    }

    isUser(){
        return this.user.role_id === 2;
    }

    isAdminOrUser(){
        if(this.user.role_id === 1 || this.user.role_id === 2){
            return true;
        }
    }
}