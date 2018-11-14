
export default class Load {
    static loadVew(page, parent) {
        $.get( "/users")
            .done(function(res) {
                if (parent && $(parent)) {
                    $(parent).html(res)
                }
                console.log( "second success" );
            })
            .fail(function() {
                console.log( "error" );
            })
            .always(function() {
                console.log( "finished" );
            });
    }
}