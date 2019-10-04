describe('my awesome website', () => {
  it('should do some chai assertions', () => {
      browser.url('https://webdriver.io')
      browser.getTitle().should.be.equal('WebdriverIO · Next-gen WebDriver test framework for Node.js')
  })
})
