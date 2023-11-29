
# Atoolo GraphQL search bundle

This bundle extends the GraphQL interface with a search.


```graphql
{
    q1: selectResources (input: {
        core:"neu-isenburg-whinchat-www"
        text: "Rubrikseite Abschnitte"
        queryDefaultOperator: OR
        filterList: [
            {key:"objectTypes", objectTypes:["content"]}
            {key:"contentSectionTypes", contentSectionTypes:["image"]}
        ]
        facetList: [
            {key:"objectTypes", objectTypes:["content", "news", "media"]}
            {key:"contentSectionTypes", contentSectionTypes:["text","image"]}
        ]
        offset: 0
    }) {
        total
        offset
        queryTime
        resourceList {
            id
            contentSectionTypes
            navigation {
                parent {
                    name
                    teaser {
                        ...teaser
                    }
                }
                root {
                    id
                    teaser {
                        ...teaser
                    }
                }
            }
            teaser {
                ...teaser
            }
        }
    }
    q2: selectResources (input: {
        core:"neu-isenburg-whinchat-www"
        text: "online"
        queryDefaultOperator: AND
        filterList: [
            {key:"objectTypes", objectTypes:["news"]}
        ]
        offset: 10
    }) {
        total
        offset
        queryTime
    }
}


fragment teaser on Teaser {
    __typename
    url
    ... on ArticleTeaser {
        headline
        asset(variant:"teaser") {
            ...asset
        }
    }
    ... on MediaTeaser {
        headline
        contentType
        contentLength
    }
}

fragment asset on Asset {
    __typename
    copyright
    caption
    description
    ... on Image {
        alternativeText
        original {
            ...imageSource
        }
        characteristic
        sources {
            ...imageSource
        }
    }
}

fragment imageSource on ImageSource {
    variant
    url
    width
    height
    mediaQuery
}
```

```graphql
{
  q1: selectResources (input: {
    core:"neu-isenburg-whinchat-www"
    text: "Rubrikseite Abschnitte"
    queryDefaultOperator: OR
    filterList: [
      {key:"objectTypes", objectTypes:["content"]}
      {key:"contentSectionTypes", contentSectionTypes:["image"]}
    ]
    facetList: [
      {key:"objectTypes", objectTypes:["content", "news", "media"]}
      {key:"contentSectionTypes", contentSectionTypes:["text","image"]}
    ]
    offset: 0
  }) {
    total
    offset
    queryTime
    resourceList {
      id
    }
    facetGroupList {
      key
      facetList {
        key
        hits
      }
    }

  }
}
```

```graphql
{
  q1: selectResources (input: {
    core:"neu-isenburg-whinchat-www"
    text: "Rubrikseite Abschnitte"
    queryDefaultOperator: OR
    filterList: [
      {key:"objectTypes", objectTypes:["content"]}
      {key:"contentSectionTypes", contentSectionTypes:["text"]}
    ]
    facetList: [
      {key:"objectTypes", objectTypes:["content", "news", "media"]}
      {key:"contentSectionTypes", contentSectionTypes:["text","image"]}
    ]
    offset: 0
  }) {
    total
    offset
    queryTime
    resourceList {
      id
      teaser {
          ...teaser
      }
    }
    facetGroupList {
      key
      facetList {
        key
        hits
      }
    }
  }
}

fragment teaser on Teaser {
    __typename
    url
    ... on ArticleTeaser {
        headline
        asset(variant:"teaser") {
            ...asset
        }
    }
    ... on MediaTeaser {
      headline
      contentType
      contentLength
    }
}

fragment asset on Asset {
   	__typename
    copyright
    caption
    description
    ... on Image {
    	  alternativeText
	      original {
          ...imageSource
        }
  	    characteristic
    	  sources {
      	  ...imageSource
      	}
	  }
}

fragment imageSource on ImageSource {
  variant
  url
  width
  height
  mediaQuery
}
```

Mehrere Abfragen auf einmal mit
https://graphql.org/learn/queries/#aliases