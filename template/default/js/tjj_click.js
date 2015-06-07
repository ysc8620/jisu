/**
 * Here will output the variable "_ylmf_click_".
 * @author <cyy0523xc@gmail.com>
 */
(function()
{
    var _ylmf_click_ = window._ylmf_click_ = new Object(), stat_server = ['www.tjj.com'], //config of server(for click stat). If there are many servers, please this variable
 o = null, u = '', n = '', img = new Image();
    
    //send query
    stat_server = get_stat_server();
    _ylmf_click_.query = function(u, n, q)
    {
        //img.src = "http://stat.demo.114la.com/test.add_click.php?u=" + encodeURIComponent(u) + "&n=" + encodeURIComponent(n) + "&q="+q+"&i=" + Math.random();
        img.src = "http://" + stat_server + "/index?u=" + encodeURIComponent(u) + "&n=" + encodeURIComponent(n) + "&q=0&i=" + Math.random();
    };
    
    _ylmf_click_.fn_handler = function()
    {
        try 
        {
            o = arguments[0];
            o = o.target || o.srcElement;
            if ('A' == o.tagName || 'A' == o.parentNode.tagName) 
            {
                u = o.href;
                n = o.innerHTML;
                //o is an image object
                if (o.parentNode.tagName == "A") 
                {
                    u = o.parentNode.href;
                    n = o.alt ? o.alt : o.title;
                }
                if (u || n) 
                {
                    if (!n) 
                    {
                        n = '';
                    }
                    n = n.replace(/\s*/g, '');
                    if (/^javascript:/.test(u)) 
                    {
                        u = '';
                    }
                    if (!u) 
                    {
                        u = n;
                    }
                    _ylmf_click_.query(u, n, 0);
                }
            }
            else 
            {
            }
        } 
        catch (e) 
        {
            //alert('error')
        }
    };
    
    /**
     * add event listener
     */
    _ylmf_click_.add_listener = function(t, e, f)
    {
        //if IE
        if (t.attachEvent) 
        {
            t.attachEvent('on' + e, f);
        }
        //if FF
        else if (t.addEventListener) 
        {
            t.addEventListener(e, f, false);
        }
        else 
        {
            t['on' + e] = f;
        }
    };

    /**
     * if there are many servers, please change the variable 'stat_server'
     */
    function get_stat_server()
    {
        return stat_server[Math.floor(Math.random() * stat_server.length)];
    }
    
    _ylmf_click_.add_listener(document.body, 'click', _ylmf_click_.fn_handler);
    
})();