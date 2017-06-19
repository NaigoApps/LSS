<style>
    
    *{
        margin: 0px;
        padding: 0px;
    }
    
    h1{
        font-family: sans-serif;
        font-size: 200%;
        margin-top: 20px;
        text-align: center;
        transition-duration: 0.5s;
        
        animation-name: test;
        animation-duration: 1s;
        animation-direction: alternate;
        animation-iteration-count: infinite;
    }
    
    @keyframes test{
        from {color : red;}
        to {color : blue;}
    }
    
    h1:hover{
        color: red;
        font-size: 250%;
        transition-duration: 0.5s;
    }
    
</style>

<h1>HELLO</h1>