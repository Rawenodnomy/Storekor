export async function feachGET(url){
    let res = fetch(url, {
        headers:{
            'Content-type':'application/json',
            'Accept': 'application/json'
        },
        method: "GET",
    })
    // console.log(res);
    return (await res).json()
}

export async function feachPOST(url, data){
    let res = fetch(url, {
        headers:{
            'Content-type':'application/json',
            'Accept': 'application/json'
        },
        method: "POST",
        body: JSON.stringify(data),
    })
    // console.log(res);
    return (await res).json()
}

