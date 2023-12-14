interface WPGraphQLParams {
    query: string
    variables?: object
}

export async function WPQuery({ query, variables }: WPGraphQLParams) {
    const response = await fetch('https://www.voxpopuli.digital/graphql', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            query,
            variables
        }),

    });

    if (!response.ok) {
        console.log(response);
        return {}
    }

    const { data } = await response.json();

    return data
}