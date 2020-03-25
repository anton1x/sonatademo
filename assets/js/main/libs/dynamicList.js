import axios from 'axios';

export default class {

  constructor(settings) {
    this.settings = Object.assign({
      nowPage : 1,
      maxAutoLoads : 5,
      nextPage : false,
      loadUrlPrefix : '',
      loadUrlPostfix : '',
      button : false,
      onLoad : ()=>{},
    }, settings);

    this.isLoading = false;
    this.autoLoadsCount = 0;

    window.addEventListener('scroll', (e) => {
      if (this.settings.button && this.settings.nextPage && this.isLoading == false)
      {
        let scroll = document.scrollingElement ? document.scrollingElement.scrollTop : window.document.body.scrollTop;
        let buttonTop = this.settings.button.getBoundingClientRect().top;
        if (buttonTop - window.innerHeight <= 0 && this.autoLoadsCount < this.settings.maxAutoLoads)
        {
          this.autoLoadsCount++;
          this.loadNext();
        }
      }
    });

    if (this.settings.button)
    {
      this.settings.button.addEventListener('click', () => {
        this.autoLoadsCount = 0;
        this.loadNext();
      });
    }
  }
  
  loadNext()
  {
    if (this.settings.nextPage)
    {
      this.isLoading = true;
      axios.get(this.settings.loadUrlPrefix + (this.settings.nowPage + 1) + this.settings.loadUrlPostfix).then((response) =>
      {
        if (response.data.error == 0)
        {
          this.settings.nextPage = response.data.nextPage;
          this.settings.nowPage++;
          this.settings.onLoad(response.data);
          this.isLoading = false;
        }
        else
        {
          this.isLoading = false; 
        }
      }).catch(()=> {
        this.isLoading = false;
      });
    }
  }

}
