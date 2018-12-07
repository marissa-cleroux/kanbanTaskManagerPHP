$(()=>{

    let displayAlbums = (albums, genre) => {
        $('#albumsDisplay').empty();
        Array.from(albums).map((album) => {
            let title = album.querySelector('albumName').firstChild.nodeValue;
            let company = album.querySelector('company').firstChild.nodeValue;
            let year = album.querySelector('year').firstChild.nodeValue;
            let condition = getCondition(album.querySelector('condition').firstChild.nodeValue);
            let price = album.querySelector('price').firstChild.nodeValue;
            let artistNode = album.querySelectorAll('artist');
            let artist = parseArtists(artistNode);

            outputAlbum(title, company, artist, year, condition, price);
        });
    };

    let getCondition = (cond) =>{
        let conditions = {
            M : "Mint",
            VG : "Very Good",
            G : "Good",
            P : "Poor",
            VP : "Very Poor"
        };

        return conditions[cond];
    };

    $('#genresDD').on('change', (e)=> {
        if(e.target.value !== '0'){
            retrieveAlbums(e.target.value);
        }
    });

    let timeoutId;

    let retrieveAlbums = (genre) =>{
        $.get(`./getAlbums.php?genre=${genre}`,
            (data) =>{
                if(timeoutId)
                    clearTimeout(timeoutId);
                let albums = data.querySelectorAll('album');
                displayAlbums(albums, genre);
            }).then(() => {
            timeoutId = setTimeout(()=>{ retrieveAlbums(genre)}, 20000);
        });
    };

    let outputAlbum = (title, company, artist, year, condition, price) =>{
        let newDiv = `<div class="album">
                                <div class="mainInfo">
                                        <h4>Title: ${title}</h4>
                                        <p>Artist: ${artist}</p>   
                                        <p>Condition: ${condition}</p>
                                </div>
                                <div class="otherInformation">
                                        <p>Company: ${company}</p>
                                        <p>Year: ${year}</p>
                                        <p>Price: ${price}</p>
                                </div>
                                        
                            </div>`;
        $('#albumsDisplay').append(newDiv);
    };

    let parseArtists = (artistNode) => {
        let artist = "";

        if(artistNode.length > 1) {
            for (let i = 0; i < artistNode.length; i++) {
                artist += artistNode[i].firstChild.nodeValue;
                if(i < artistNode.length - 2)
                    artist += ", ";
                else if(i < artistNode.length - 1)
                    artist += " and ";
            }
        } else
            artist = artistNode[0].firstChild.nodeValue;

        return artist;
    };

    $(window).on('resize', ()=>{
        if(innerWidth > 1200){

        }
    });
});

// display different icons depending on letter starting with
// what could I use as an icon