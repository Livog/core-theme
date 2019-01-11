# Core - Wordpress REST API Theme
Core is a good base for making your Wordpress site into a content API instead. Mostly used with third-party JS frameworks like Vue.js, React or Angular. **This is not a regular Wordpress theme**

## Requirments 

[Advanced Custom Fields Plugin](https://www.advancedcustomfields.com/)

## Non Requirments 

### Redirects

:white_check_mark: [Yoast Pro](https://yoast.com/)

### Translations

:white_check_mark: [WPML](https://wpml.org/) 

## Routes
Routes that the theme creates.

**Pages & Posts**
```
/api/core/v2/<post|page>/<route|id|empty>
```
**Menus**
```
/api/core/v2/menus
/api/core/v2/menus/<id|location>
```
**Sitemap** - When you want to create the sitemap in your js framework.
```
/api/core/v2/sitemap
```
**Redirects** - For redirect urls.
```
/api/core/v2/redirects
```

### About

There are many companies out there trying to create a headless CMS that have the same functionality as ACF has but within a more faster framework. We know that Wordpress isn't the fastest CMS and is pretty heavy from the beginning. We are talking about milliseconds, uncached data and how many databases request that's needed for each page. Regards that it's slower out of the box it still works much better than any other CMS out there. Of course, there could be CMS that work better, but I have not found any that is a headless CMS with flexible content, repeatable fields within repeatable fields so until then I m publishing this for anyone to use as a great base to work whit. This is not to fit you needs to 100% but should help you along the way.

### Why headless?
Wordpress backend & frontend is very tangled together, so what sometimes you do is split up backend and frontend. By splitting them up could help for big teams or workflow as the two are not reliant on each other. Splitting up frontend and backend code is excellent for several reasons. Each party can focus on what they do best. Front-end will never need to set up a backend project, and the other way around. The other reason is that when a better and faster headless CMS comes along, you can migrate much easier to that one and not even change the front end framework.

### The goal
**Goals are important!** If it's something you would like to change or help this theme with feel free to do fork it and send me a pull request. The goal is to have a good best practice code with as little complications as possible. If we can minimize requests or making something faster or more understandable, you should do it and commit it :)

Feel free to send me a message on Discord `Livog#1490`.
