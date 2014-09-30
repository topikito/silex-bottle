# Silex bottle

## What's this?

So, this is basically an app-driven structure I've created for personal projects and I've found useful as "copy-paste" resource for fast web development.

## How do I use this?

This is a *Silex* based project, so you should already know a little about how *Silex* or *Symfony 2* works.

As mentioned, its a *Silex* project, inspired in the layout *Symfony* forces you to use, but of course, more flexible (Silex way). Its based on kind of an MVC layout, just the "M" part has diverged a little. In this project I use something I've called `SAL` (Storage Abstraction Layer), where you can have whatever you need to access data: models (SQL), documents (NoSQL), DataMappers (Custom), FileSystem (getting content of local storage, for example),... So there was a "risk" of having multiple folders on the same level as the `config` and the `controller` folder, therefor I've used this `SAL` structure.

Notice the framework is app-driven. So you should encapsule your projects inside a `PSR-0` layout. You declare your apps inside the `app\Config` `Bootstrap` file, by calling an `Instructor`. Instructors are environment loaders (application config loaders) so the core of the framework knows what to load and how to load it.

The main purpose of this project is not really having many apps inside the same repository. Its more about having the core functionality decoupled from the dynamic layout a particular web can have. This allows me to integrate whatever I need inside the `app` folder and easily share (copy and paste) between other projects.

If you are seeking for a more "maintainable" way of doing this, check out my repo [SiCMS](https://github.com/topikito/SiCMS) (which uses [Silex MVC](https://github.com/topikito/Silex-MVC) as a package). Here you can develop core functionality in the MVC repo and then deploy as a new release. After you've released from Silex MVC, you can update via composer in SiCMS and use the functionality. You can easily maintain various projects using this methodoloy.

## Disclaimer

### Folder naming
I did not really have a clear decission of which folders should have first letter capitalized and which ones should just be lowercase, so I've used a pretty arbitrary rule: if theres code straigth ahead inside the folder, then its uc-first, if theres more than code, and its not straight ahead, then lowercase. I know its not the best  practice, but as said, I tried to emulate Symfony and I did not get that part really right.

### Stability
This have been copied directly from another project and "search/replace" the sensible naming. Also, deleted whatever was not common. Therefor, I haven't tested this code and __may NOT work out of the box__. Be aware of this.
 