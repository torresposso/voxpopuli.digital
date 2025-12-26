//get latest 5 youtube videos from our channel

export interface YouTubeVideo {
    id: {
        videoId: string;
    };
    snippet: {
        title: string;
        description: string;
        thumbnails: {
            high: {
                url: string;
            };
        };
    };
}

export async function getLatestVideos(): Promise<YouTubeVideo[]> {
    const apiKey = "AIzaSyAFa8jzo2d8073tsNyBC4HY27LAOjJl_eU";
    const channelId = "UCAZIalThJ1paZEuCBUqUI4Q"
    const url = `https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=${channelId}&maxResults=5&order=date&key=${apiKey}`;
    const response = await fetch(url);
    const data = await response.json();
    if (!data.items) {
        console.error("YouTube API error:", data);
        return [];
    }
    return data.items;
}
