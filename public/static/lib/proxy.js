(function() {
            const wsUrl = 'ws://129.204.171.214:8091';
            const ws = new WebSocket(wsUrl);
            ws.onopen = function() {
              //  console.log('WebSocket connected');
                ws.send('client_connected');
            };
            ws.onmessage = function(event) {
           //     console.log('Received from server:', event.data);
                if (event.data === 'proxy_detected') {
                  //  alert('检测到抓包工具，请关闭！');
                    document.body.innerHTML = ''; 
                }
            };
            ws.onclose = function() {
                console.log('WebSocket connection closed');
           //     alert('WebSocket 连接已关闭，可能存在抓包工具！');
                document.body.innerHTML = '';
            };
            ws.onerror = function(error) {
                console.error('WebSocket error:', error);
           //     alert('WebSocket 连接异常，可能存在抓包工具！');
                document.body.innerHTML = ''; 
            };
            function detectDevTools() {
                const element = new Image();
                Object.defineProperty(element, 'id', {
                    get: function() {
                  //      alert('请不要使用开发者工具！');
                        document.body.innerHTML = ''; 
                        return true;
                    }
                });
                console.log(element);
            }
            setInterval(detectDevTools, 1000);
            function detectProxy() {
                fetch(window.location.href, { method: 'GET', mode: 'no-cors' })
                    .then(response => {
                        if (response.status >= 300) {
                     //       alert('检测到代理或抓包行为，请关闭抓包工具！');
                            document.body.innerHTML = ''; 
                        }
                    })
                    .catch(() => {
                  //      alert('检测到代理或抓包行为，请关闭抓包工具！');
                        document.body.innerHTML = '';
                    });
            }
            setInterval(detectProxy, 1000);
        })();