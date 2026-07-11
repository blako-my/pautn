/*(function() {
    // 1. Capture the username hash BEFORE cleaning
    const currentHash = window.location.hash;
    // 2. Check if the "junk" (search params) exists
    if (window.location.search.length > 0) {
      // 3. Rebuild: Base URL + the Hash we saved
        const cleanUrl = window.location.origin + window.location.pathname + currentHash;

      // 4. Update the address bar
        window.history.replaceState({}, document.title, cleanUrl);
    }
})();
*/
function showdialog(dialog,content,mode=null)
{
    content = `<button class="btn float-end text-danger" onclick="document.getElementById('dialog').close()">x</button><br>` + content;
    dialog.innerHTML = content;
    if(mode != null)
    {
        switch (mode) {
            case 'error':
                dialog.className = 'border border-2 border-danger';
                break;
            default:
                break;
        }
    }
    dialog.showModal();
}
async function generatepage(id){
    const dialog = document.getElementById('dialog');
    const filepath = `data/${id}.user`;
    try{
        const response = await fetch(filepath);
        const data = await response.json();
        if(data !== "")
        {
            //* THEME
            const activetheme = data.theme || 'default';
            document.body.setAttribute('th-theme', activetheme);
            userasset = 'uploads/' + id + '/';
            //* PROFILE
            profilediv = document.getElementById('profile');
            profile = data.profile;
            let favicon = document.querySelector('link[rel="icon"]');
            if(profile.img && profile.img.trim() !== "")
            {
                favicon.href = profile.img.replace("@/", userasset);
                const profileimg = document.createElement('img');
                profileimg.src = profile.img.replace("@/",userasset);
                profileimg.classList.add('rounded-circle');
                profilediv.appendChild(profileimg);
            }
            if(profile.username && profile.username.trim()  !== "")
            {
                usernametxt = '@'+profile.username;
                if(profile.name && profile.name.trim()  !== "")
                {
                    labeltxt = profile.name;
                }
                else
                {
                    labeltxt = usernametxt;
                }
                document.title = labeltxt;
                const username = document.createElement('h3');
                username.textContent = usernametxt;
                profilediv.appendChild(username);
            }
            if(profile.bio && profile.bio.trim() !== ""){
                const bio = document.createElement('p');
                bio.classList.add('w-50','mx-auto','opacity-75');
                bio.textContent = profile.bio;
                profilediv.appendChild(bio);
            }
            //* SOCIALS
            socials = data.socials;
            if(socials && socials.trim !== "")
            {
                container = document.createElement('div');
                container.id = 'socials';
                socials.forEach(social =>{
                    const element = document.createElement('a');
                    const label = social.name.toLowerCase().split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
                    element.href = social.url;
                    element.classList.add('th-fg');
                    element.target = '_blank';
                    element.title = label;
                    if(social.icon && social.icon.trim() !== "") {
                        const img = document.createElement('i');
                        img.className = social.icon;
                        element.appendChild(img);
                    }
                    else
                    {
                        element.textContent = social.name;
                    }
                    container.appendChild(element);
                });
                profilediv.appendChild(container);
            }
            //* SECTIONS
            const sectiondiv = document.getElementById('sections');
            sections = data.sections;
            sections.forEach(section => {
                if(section.title && section.title.trim() !== "")
                {
                    h = document.createElement('h3');
                    h.innerHTML = section.title;
                    h.classList.add('text-center');
                    sectiondiv.appendChild(h);
                }

                if(section.type == "links" && section.content )
                {
                    container = document.createElement('div');
                    container.className = "links";
                    section.content.forEach(link =>{
                        element = document.createElement('a');
                        element.href = link.url;
                        element.target = '_blank';
                        element.textContent = link.name;
                        element.classList.add( 'th-fg', 'th-border');
                        container.appendChild(element);
                    });
                    sectiondiv.appendChild(container);
                }
                else if(section.type == "products" && section.content )
                {
                    container = document.createElement('div');
                    container.className = 'products';
                    section.content.forEach(product =>{
                        element = document.createElement('a');
                        element.textContent = product.label;
                        element.classList.add('th-border', 'p-2', 'm-1');
                        // Add Image if it exists
                        if (product.img && product.img.trim() !== "") {
                            const img = document.createElement('img');
                            img.src = product.img.replace("@/", userasset);
                            img.alt = product.label;
                            element.appendChild(img);
                        }
                        if (product.url && product.url.trim() !== "") {
                            element.href = product.url;
                            element.target = '_blank';
                        }
                        container.appendChild(element);
                    });
                    sectiondiv.appendChild(container);
                }
            });
        }
    }
    catch(error){
        //showdialog(dialog,error,'error');
        window.location.href = "error.html";
        return;
    }
}