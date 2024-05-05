if (!window.SPARouter) window.SPARouter = {
    update: html => {
        const parser = new DOMParser();
        const newDoc = parser.parseFromString(html, 'text/html');
        document.head.innerHTML = newDoc.head.innerHTML;
        document.body.innerHTML = newDoc.body.innerHTML;
    },
    element: element => {
        if (element.hasAttribute('spa-router-loaded')) return;
        element.setAttribute('spa-router-loaded', '')
    
        let url = element.getAttribute('href');
    
        element.addEventListener('click', async event => {
            event.preventDefault();
    
            try {
                const html = await (await fetch(url)).text();
    
                if (document.startViewTransition) {
                    document.startViewTransition(() => window.SPARouter.update(html))
                } else {
                    window.SPARouter.update(html)
                }
    
                if (url.endsWith('.html')) url = url.slice(0, -5);
    
                window.history.pushState({}, '', url);
            } catch (error) {}
        })
    },    
    elements: () => {
        const elements = document.querySelectorAll('a[href^="/"]:not([spa-router-loaded]), a:not([spa-router-loaded], [href^="http"], [href^="https"])')
            
        elements.forEach(window.SPARouter.element)
    },
    load: () => {
        if (window.routerObserver) return;

        window.routerObserver = new MutationObserver(window.SPARouter.elements);
                
        window.routerObserver.observe(document.body, {
            attributes: true,
            characterData: true,
            childList: true,
            subtree: true,
            attributeOldValue: true,
            characterDataOldValue: true
        });
    
        document.head.innerHTML += `
            <style>
                ::view-transition-old(root),
                ::view-transition-new(root) {
                    animation-duration: 0.3s;
                }
            </style>
        `
    }
}; 

document.addEventListener('DOMContentLoaded', window.SPARouter.load());
