Vagrant.configure("2") do |config|
  config.vm.box = "precise32"
  config.vm.hostname = "osteele-dev"
  config.vm.provision :shell, :path => "config/bootstrap.sh"
  config.vm.network :forwarded_port, host: 8081, guest: 80
  config.cache.auto_detect = true if Vagrant.has_plugin?("vagrant-cachier")
end
